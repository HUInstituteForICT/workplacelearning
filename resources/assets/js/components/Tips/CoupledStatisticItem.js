import axios from "axios";
import {normalize} from "normalizr";
import {Schema} from "../../Schema";
import React from "react";

const CoupledStatisticItem = ({tip, coupledStatistic, statistic, educationProgramType, editMode, updateEntity, toggleEditModeForCoupledStatistic, decoupleStatistic}) => {

    const saveCoupledStatistic = () => {
        axios.put(`/api/tip-coupled-statistics/${coupledStatistic.id}`, {
            threshold: coupledStatistic.threshold,
            comparison_operator: coupledStatistic.comparison_operator,

        }).then(response => {
            const normalizedCoupledStatistic = normalize(response.data, Schema.coupledStatistic).entities.coupledStatistics[coupledStatistic.id];
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
                    <h5>
                        {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                        {statistic.type !== 'predefinedstatistic' && statistic.name}
                    </h5>

                    <strong>{Lang.get('tips.ep-type')}:</strong> {statistic.education_program_type}<br/>
                    <strong>{Lang.get('tips.condition')}: </strong>{coupledStatistic.condition}<br/>
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
                <h5>
                    {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                    {statistic.type !== 'predefinedstatistic' && statistic.name}
                </h5>

                <strong>{Lang.get('statistics.when-active')}</strong>
                <div className="row">
                    <div className="col-lg-6" id="step-13">
                        <select
                            value={coupledStatistic.comparison_operator} className="form-control"
                            onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                                ...coupledStatistic,
                                comparison_operator: e.target.value
                            })}>
                            <option value="1">{Lang.get('statistics.greater-than')}</option>
                            <option value="0">{Lang.get('statistics.less-than')}</option>
                        </select>
                    </div>
                    <div className="col-lg-6">
                        <input type="number" className="form-control" step="any" value={coupledStatistic.threshold}
                               onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                                   ...coupledStatistic,
                                   threshold: e.target.value
                               })}/>
                    </div>
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

export default CoupledStatisticItem;
