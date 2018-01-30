import {connect} from "react-redux";
import React from "react";
import {actions as coupleStatisticActions} from "./redux/coupleStatistic";
import {actions as entityActions} from "./redux/entities";
import axios from "axios/index";
import {availableStatistic, coupledStatistic} from "../../Schema";
import {normalize} from "normalizr";
import CreateForm from "../Statistics/CreateForm";

const TipEditPage = ({match, tip, entities, coupleStatisticForm, updateCoupleStatisticFormProperty, storeNewCoupledStatistic, storeNewStatisticVariable}) => {
    if (tip === undefined) return <div>Loading...</div>;

    return <div className="container">
        <h1>{tip.name}</h1>


        <h3>{Lang.get('tips.coupled-statistics')}</h3>
        <div style={{display: 'flex', flexDirection: 'column'}}>
            {
                tip.coupled_statistics.map(coupledStatisticId => {

                    const coupledStatistic = entities.coupledStatistics[coupledStatisticId];
                    const statistic = entities.statistics[coupledStatistic.statistic_id];

                    return <div key={coupledStatisticId} className="panel panel-default" style={{flex: '1'}}>
                        <div className="panel-body">
                            <div>
                                <h5>{statistic.name}</h5>
                                <strong>{Lang.get('tips.ep-type')}:</strong> {entities.educationProgramTypes[statistic.education_program_type_id].eptype_name}<br/>
                                <strong>{Lang.get('tips.condition')}:</strong> {coupledStatistic.condition}<br/>
                                <strong>{Lang.get('tips.multiplyBy100')}:</strong> {coupledStatistic.multiplyBy100 ? Lang.get('react.yes') : Lang.get('react.no')}
                            </div>
                            <br/>
                            <button className="btn btn-danger">{Lang.get('react.delete')}</button>
                        </div>

                    </div>;
                })
            }
        </div>


        <div className="row">
            <div className="col-md-5">
                <h3>{Lang.get('tips.couple-statistic')}</h3>
                <div>
                    <div className="form-group">
                        <label>{Lang.get('tips.form.statistic')}</label>
                        <select value={coupleStatisticForm.statistic} className="form-control"
                                onChange={e => updateCoupleStatisticFormProperty('statistic', e.target.value)}>
                            <option disabled={true}/>
                            {

                                Object.values(entities.availableStatistics).map(
                                    statistic => <option key={statistic.id}
                                                         value={statistic.id}>
                                        {statistic.name}&nbsp;-&nbsp;
                                        ({entities.educationProgramTypes[statistic.education_program_type].eptype_name})
                                        {statistic.type === 'predefinedstatistic' && ' - (Predefined)'}
                                    </option>
                                )
                            }
                        </select>
                    </div>

                    <div className="form-group">
                        <label>{Lang.get('tips.form.comparison-operator')}</label>
                        <select value={coupleStatisticForm.comparisonOperator} className="form-control"
                                onChange={e => updateCoupleStatisticFormProperty('comparisonOperator', e.target.value)}>
                            <option value="0">&lt;</option>
                            <option value="1">&gt;</option>
                        </select>
                    </div>

                    <div className="form-group">
                        <label>{Lang.get('tips.form.threshold')}</label>
                        <input type="number" className="form-control" step="any" value={coupleStatisticForm.threshold}
                               onChange={e => updateCoupleStatisticFormProperty('threshold', e.target.value)}/>
                    </div>

                    <div className="form-group">
                        <label>
                            <input type="checkbox" checked={coupleStatisticForm.multiplyBy100}
                                   onChange={e => updateCoupleStatisticFormProperty('multiplyBy100', e.target.checked)}/>
                            &nbsp;{Lang.get('tips.form.multiplyBy100')}
                        </label>
                    </div>

                    <button className="btn defaultButton"
                            disabled={coupleStatisticForm.statistic === '' || coupleStatisticForm.threshold === '' || coupleStatisticForm.comparisonOperator > '2'}
                            onClick={() => {

                                axios.post('/api/tip-coupled-statistics', {
                                    tip_id: tip.id,
                                    statistic_id: coupleStatisticForm.statistic,
                                    threshold: coupleStatisticForm.threshold,
                                    method: entities.availableStatistics[coupleStatisticForm.statistic].hasOwnProperty('method') ? entities.availableStatistics[coupleStatisticForm.statistic].method : null,
                                    comparisonOperator: coupleStatisticForm.comparisonOperator,
                                    multiplyBy100: coupleStatisticForm.multiplyBy100
                                }).then(response => {
                                    storeNewCoupledStatistic(normalize(response.data, coupledStatistic))
                                })
                            }}>{Lang.get('tips.save')}</button>
                </div>
            </div>

            <div className="col-md-5 col-md-offset-2">
                <h3>{Lang.get('react.statistic.create-statistic')}</h3>
                <CreateForm statisticVariables={Object.values(entities.availableStatisticVariables)} educationProgramTypes={entities.educationProgramTypes}
                            operators={[
                                {type: 0, label: "+"},
                                {type: 1, label: "-"},
                                {type: 2, label: "*"},
                                {type: 3, label: "/"},
                            ]}
                            onCreated={newEntity => {
                                storeNewStatisticVariable(normalize(newEntity, availableStatistic));
                            }}
                />
            </div>
        </div>


    </div>
};

const mapping = {
    state: (state, props) => ({
        tip: state.entities.tips[props.match.params.id],
        entities: state.entities,
        coupleStatisticForm: state.coupleStatistic
    }),
    dispatch: dispatch => ({
        updateCoupleStatisticFormProperty: (property, value) => dispatch(coupleStatisticActions.updateCoupleStatisticFormProperty(property, value)),
        storeNewCoupledStatistic: normalized => {

            dispatch(entityActions.addEntities(normalized.entities));
            dispatch(entityActions.addCoupledStatisticToTip(normalized.result, normalized.entities.coupledStatistics[normalized.result].tip_id));
        },
        storeNewStatisticVariable: normalized => {
            dispatch(entityActions.addEntities(normalized.entities));

        }
    })
};

export default connect(mapping.state, mapping.dispatch)(TipEditPage);