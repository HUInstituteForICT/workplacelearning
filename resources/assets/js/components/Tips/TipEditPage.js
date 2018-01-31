import {connect} from "react-redux";
import React from "react";
import {actions as coupleStatisticActions} from "./redux/coupleStatistic";
import {actions as entityActions} from "./redux/entities";
import {actions as uiActions} from "./redux/tipPageUi";
import axios from "axios/index";
import {availableStatistic, coupledStatisticSchema} from "../../Schema";
import {normalize} from "normalizr";
import CreateForm from "../Statistics/CreateForm";

// Should connect smaller components themselves because this looks ridiculous as props
const TipEditPage = ({match, tip, entities, coupleStatisticForm, updateCoupleStatisticFormProperty, coupledStatisticsInEditMode, storeNewCoupledStatistic, storeNewStatisticVariable, updateEntity, toggleEditModeForCoupledStatistic, decoupleStatistic}) => {
    if (tip === undefined) return <div>Loading...</div>;

    return <div className="container">
        <h1>{Lang.get('tips.edit')}</h1>
        <div className="input-group input-group-lg">
            <input type="text" className="form-control" placeholder={Lang.get('tips.name')} value={tip.name}
                   onChange={e => updateEntity('tips', tip.id, {...tip, name: e.target.value})}/>
        </div>


        {/* The list op coupled statistics for this tip */}
        <h3>{Lang.get('tips.coupled-statistics')}</h3>
        <div style={{display: 'flex', flexDirection: 'column'}}>
            {
                tip.coupled_statistics.sort().map(coupledStatisticId => {

                    const coupledStatistic = entities.coupledStatistics[coupledStatisticId];
                    const statistic = entities.statistics[coupledStatistic.statistic_id];

                    return <CoupledStatisticItem key={coupledStatisticId} coupledStatistic={coupledStatistic} tip={tip}
                                                 statistic={statistic}
                                                 educationProgramType={entities.educationProgramTypes[statistic.education_program_type_id]}
                                                 editMode={coupledStatisticsInEditMode.includes(coupledStatisticId)}
                                                 updateEntity={updateEntity}
                                                 toggleEditModeForCoupledStatistic={toggleEditModeForCoupledStatistic}
                                                 decoupleStatistic={decoupleStatistic}
                    />
                })
            }
        </div>


        <div className="row">
            <div className="col-md-5">
                {/* Couple a statistic to the tip */}
                <h3>{Lang.get('tips.couple-statistic')}</h3>
                <div>
                    <div className="form-group">
                        <label>{Lang.get('tips.form.statistic')}</label>
                        <select value={coupleStatisticForm.statistic} className="form-control"
                                onChange={e => updateCoupleStatisticFormProperty('statistic', e.target.value)}>
                            <option disabled={true}/>
                            {

                                allowedAvailableStatistic(tip, Object.values(entities.availableStatistics), entities.coupledStatistics, entities.statistics).map(
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
                                    storeNewCoupledStatistic(normalize(response.data, coupledStatisticSchema))
                                })
                            }}>{Lang.get('tips.save')}</button>
                </div>
            </div>

            <div className="col-md-5 col-md-offset-2">
                <h3>{Lang.get('react.statistic.create-statistic')}</h3>
                <CreateForm statisticVariables={Object.values(entities.availableStatisticVariables)}
                            educationProgramTypes={entities.educationProgramTypes}
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
        <hr/>

        <div className="form-group">
            <label>Tip text</label>
            <textarea className="form-control" value={tip.tipText} maxLength={1000} rows={3}
                      onChange={e => updateEntity('tips', tip.id, {...tip, tipText: e.target.value})}/>
        </div>
        <p>{Lang.get('tips.form.statistic-value-parameters')}</p>

        {/* Table for value parameters */}
        <table className="table">
            <thead>
            <tr>
                <th>{Lang.get('tips.form.table-statistic')}</th>
                <th>{Lang.get('tips.form.table-value-parameter')}</th>
                <th>{Lang.get('tips.form.table-value-name-parameter')}</th>
            </tr>
            </thead>
            <tbody>
            {
                tip.coupled_statistics.sort().map(id => {
                    const coupledStatistic = entities.coupledStatistics[id];

                    const statistic = entities.statistics[coupledStatistic.statistic_id];

                    return <tr key={coupledStatistic.id}>
                        <td>{statistic.name}</td>
                        <td><strong>:value-{coupledStatistic.id}</strong></td>
                        <td>
                            {
                                statistic.type === 'predefinedstatistic' &&
                                <span>
                                    <strong>:value-parameter-{coupledStatistic.id}</strong>
                                    <br/>
                                    {statistic.valueParameterDescription}
                                </span>
                            }
                        </td>
                    </tr>
                })
            }
            </tbody>
        </table>
        <hr/>

        <div className="form-group">
            <input type="checkbox" checked={tip.showInAnalysis}
                   onChange={e => updateEntity('tips', tip.id, {...tip, showInAnalysis: e.target.checked})}/>
            <label>&nbsp;{Lang.get('tips.form.showInAnalysis')}</label>
        </div>

        <hr/>

        <h3>{Lang.get('tips.form.cohorts-enable')}</h3>

        <div className="row">
            <div className="col-md-4">
                <div className="form-group">
                    <label>{Lang.get('tips.form.enabledCohorts')}</label>
                    <select className="form-control" value={tip.enabled_cohorts} multiple={true}
                            onChange={e => {

                                const enabled = [...tip.enabled_cohorts];
                                if (enabled.includes(parseInt(e.target.value))) {
                                    enabled.splice(enabled.indexOf(parseInt(e.target.value)));
                                } else {
                                    enabled.push(parseInt(e.target.value));
                                }
                                updateEntity('tips', tip.id, {
                                    ...tip,
                                    enabled_cohorts: enabled
                                });
                                console.log(tip.enabled_cohorts);
                            }}>
                        {Object.values(entities.cohorts).filter(cohort => {
                            if (tip.coupled_statistics.length === 0) return true;
                            const epMapping = {
                                1: 2,
                                2: 1,
                            };
                            const epTypeId = entities.statistics[entities.coupledStatistics[tip.coupled_statistics[0]].statistic_id].education_program_type;
                            return epMapping[cohort.ep_id] === epTypeId;
                        }).sort().map(cohort => {
                            return <option key={cohort.id} value={cohort.id}>{cohort.name}</option>
                        })}
                    </select>
                </div>
            </div>
        </div>


        <button className="btn btn-primary" onClick={() => {
            axios.put(`/api/tips/${tip.id}`, tip).then(response => console.log(response));
        }}>{Lang.get('general.save')}</button>

    </div>
};

const CoupledStatisticItem = ({tip, coupledStatistic, statistic, educationProgramType, editMode, updateEntity, toggleEditModeForCoupledStatistic, decoupleStatistic}) => {

    const saveCoupledStatistic = () => {
        axios.put(`/api/tip-coupled-statistics/${coupledStatistic.id}`, {
            threshold: coupledStatistic.threshold,
            comparison_operator: coupledStatistic.comparison_operator,
            multiplyBy100: coupledStatistic.multiplyBy100,
        }).then(response => {
            const normalizedCoupledStatistic = normalize(response.data, coupledStatisticSchema).entities.coupledStatistics[coupledStatistic.id];
            updateEntity('coupledStatistics', coupledStatistic.id, normalizedCoupledStatistic);
            toggleEditModeForCoupledStatistic(coupledStatistic.id);
        });
    };

    const decouple = () => {
        axios.delete(`/api/tip-coupled-statistics/${coupledStatistic.id}`).then(response => {
            decoupleStatistic(coupledStatistic);
        });
    };

    // Render the normal display form
    if (!editMode) {
        return <div className="panel panel-default" style={{flex: '1'}}>
            <div className="panel-body">
                <div>
                    <h5>{statistic.name}</h5>

                    <strong>{Lang.get('tips.ep-type')}:</strong> {educationProgramType.eptype_name}<br/>
                    <strong>{Lang.get('tips.condition')}: </strong>{coupledStatistic.condition}<br/>
                    <strong>{Lang.get('tips.multiplyBy100')}:</strong> {coupledStatistic.multiplyBy100 ? Lang.get('general.yes') : Lang.get('general.no')}
                </div>
                <br/>
                <button className="btn btn-primary"
                        onClick={() => toggleEditModeForCoupledStatistic(coupledStatistic.id)}>{Lang.get('general.edit')}</button>
            </div>
        </div>;
    }

    // Render the edit form for a coupled statistic
    return <div className="panel panel-default" style={{flex: '1'}}>
        <div className="panel-body">
            <div>
                <h5>{statistic.name}</h5>

                <div className="form-group">
                    <label>{Lang.get('tips.form.threshold')}</label>
                    <input type="number" className="form-control" step="any" value={coupledStatistic.threshold}
                           onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                               ...coupledStatistic,
                               threshold: e.target.value
                           })}/>
                </div>

                <div className="form-group">
                    <label>{Lang.get('tips.form.comparison_operator')}</label>
                    <select
                        value={coupledStatistic.comparison_operator} className="form-control"
                        onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                            ...coupledStatistic,
                            comparison_operator: e.target.value
                        })}>
                        <option value="0">&lt;</option>
                        <option value="1">&gt;</option>
                    </select>
                </div>

                <div className="form-group">
                    <label>
                        <input type="checkbox" checked={coupledStatistic.multiplyBy100}
                               onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                                   ...coupledStatistic,
                                   multiplyBy100: e.target.checked
                               })}
                        />
                        &nbsp;{Lang.get('tips.form.multiplyBy100')}
                    </label>
                </div>
            </div>
            <br/>
            <div style={{display: 'flex', flexDirection: 'row'}}>
                <button className="btn btn-primary" onClick={saveCoupledStatistic}>{Lang.get('tips.save')}</button>
                &nbsp;
                <button className="btn btn-danger" onClick={decouple}>{Lang.get('tips.decouple')}</button>
                &nbsp;
                <button className="btn"
                        onClick={() => toggleEditModeForCoupledStatistic(coupledStatistic.id)}>{Lang.get('tips.cancel')}</button>
            </div>
        </div>
    </div>;


};

/**
 * Check which statistics can be selected for a tip
 * This changes when at least 1 tip is coupled, then that Tip's educationprogramtype is leading
 */
const allowedAvailableStatistic = (tip, availableStatistics, coupledStatistics, statistics) => {
    if (tip.coupled_statistics.length === 0) return availableStatistics;

    const allowedEpTypeId = statistics[coupledStatistics[tip.coupled_statistics[0]].statistic_id].education_program_type;

    return availableStatistics.filter(stat => parseInt(stat.education_program_type_id) === parseInt(allowedEpTypeId));
};

const mapping = {
    state: (state, props) => ({
        tip: state.entities.tips[props.match.params.id],
        entities: state.entities,
        coupleStatisticForm: state.coupleStatistic,
        coupledStatisticsInEditMode: state.tipEditPageUi.inEditMode
    }),
    dispatch: dispatch => ({
        updateCoupleStatisticFormProperty: (property, value) => dispatch(coupleStatisticActions.updateCoupleStatisticFormProperty(property, value)),
        storeNewCoupledStatistic: normalized => {

            dispatch(entityActions.addEntities(normalized.entities));
            dispatch(entityActions.addCoupledStatisticToTip(normalized.result, normalized.entities.coupledStatistics[normalized.result].tip_id));
        },
        storeNewStatisticVariable: normalized => {
            dispatch(entityActions.addEntities(normalized.entities));
        },
        updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
        toggleEditModeForCoupledStatistic: id => dispatch(uiActions.toggleEditModeCoupledStatistic(id)),
        decoupleStatistic: coupledStatistic => dispatch(entityActions.decoupleStatisticFromTip(coupledStatistic)),
    })
};

export default connect(mapping.state, mapping.dispatch)(TipEditPage);


