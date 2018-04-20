import {connect} from "react-redux";
import React from "react";
import {Link} from "react-router-dom";
import {normalize} from "normalizr";
import {Schema} from "../../Schema";
import {actions} from "./redux/entities";
import axios from "axios";

const IndexPage = ({match, history, tips, statistics, newTip}) => {
    return <div>
        <h1>Tips</h1>

        <button type="button" className="btn btn-primary" onClick={() => {
            axios.post('/api/tips', {}).then(response => {
                const normalized = normalize(response.data, Schema.tip);
                newTip(normalized.entities);
                history.push(`/tip/${normalized.result}`)
            })
        }}>
            {Lang.get('tips.new')}
        </button>

        <div style={{display: 'flex', flexDirection: 'row', flexWrap: 'wrap'}}>
            {Object.values(tips).map(tip => <TipItemContainer key={tip.id} tip={tip}/>)}
        </div>

        <h1>{Lang.get('tips.statistics')}</h1>
        <div style={{display: 'flex', flexDirection: 'row', flexWrap: 'wrap'}}>
            {Object.values(statistics).map(statistic => {
                // If an id contains p-p- or p-a- it is not a statistic of a tip but rather a statistic that can be chosen for coupling to a tip, thus skip
                // Also skip predefined statistics that have embodied a Statistic (they are but mere wrappers, no content)
                if (String(statistic.id).includes(['p-p-', 'p-a-']) || statistic.type === 'predefinedstatistic') return null;
                return <StatisticItemContainer key={statistic.id} statistic={statistic}/>
            })}
        </div>
    </div>
};

const mapping = {
    state: state => ({
        entities: state.entities,
        tips: state.entities.tips,
        statistics: state.entities.statistics,
    }),
    dispatch: dispatch => ({
        newTip: entities => dispatch(actions.addEntities(entities)),
    })
};
export default connect(mapping.state, mapping.dispatch)(IndexPage);

const statisticItem = ({statistic, educationProgramType, removeStatistic}) => {

    return <div key={statistic.id} className="panel panel-default"
                style={{minWidth: 300, maxWidth: 300, margin: 20}}>
        <div className="panel-body" style={{wordWrap: 'normal'}}>
            <strong>{Lang.get('react.tips.name')}:</strong> {statistic.name}<br/>
            <strong>{Lang.get('react.tips.program')}:</strong> {educationProgramType.eptype_name || '-'}<br/>

            <br/><br/>

            <div style={{display: 'flex', flexDirection: 'row', justifyContent: 'space-between'}}>
                <Link to={`/statistic/${statistic.id}`}>
                    <span className="btn btn-primary">{Lang.get('react.edit')}</span>
                </Link>

                <button className="btn btn-danger" onClick={() => {
                    axios.delete(`/api/statistics/${statistic.id}`).then(response => {
                        removeStatistic(statistic.id);
                    });
                }}>{Lang.get('react.delete')}</button>
            </div>
        </div>
    </div>
};

const statisticItemMapping = {
    state: (state, props) => {
        const educationProgramType = state.entities.educationProgramTypes[props.statistic.education_program_type];
        return {
            educationProgramType,
            ...props
        };
    },
    dispatch: (dispatch) => {
        return {
            removeStatistic: id => dispatch(actions.removeStatistic(id)),
        };
    }
};

const StatisticItemContainer = connect(statisticItemMapping.state, statisticItemMapping.dispatch)(statisticItem);

const tipItem = ({tip, educationProgramType, removeTip}) => {
    return <div key={tip.id} className="panel panel-default"
                style={{minWidth: 230, margin: 20}}>
        <div className="panel-body">
            <strong>{Lang.get('react.tips.name')}:</strong>&nbsp;{tip.name}<br/>
            <strong>{Lang.get('react.tips.program')}:</strong>&nbsp;
            {/* Get correct education program type if it exists for tip */}
            {educationProgramType === null && '-'}
            {educationProgramType !== null && educationProgramType.eptype_name}
            <br/>

            <strong>{Lang.get('react.tips.statistics')}:</strong> {tip.coupled_statistics.length}<br/>
            <br/>

            <strong>Likes</strong>
            <br/><br/>
            <div className="row">
                <div className="col-md-6" style={{textAlign: 'center'}}>
                    <span className="glyphicon glyphicon-thumbs-up"/>&nbsp;{tip.likes.filter(like => like.type === 1).length}
                </div>
                <div className="col-md-6" style={{textAlign: 'center'}}>
                    <span className="glyphicon glyphicon-thumbs-down"/>&nbsp;{tip.likes.filter(like => like.type === -1).length}
                </div>
            </div>

            <br/><br/>

            <div style={{display: 'flex', flexDirection: 'row', justifyContent: 'space-between'}}>
                <Link to={`/tip/${tip.id}`}>
                    <span className="btn btn-primary">{Lang.get('react.edit')}</span>
                </Link>

                <button className="btn btn-danger" onClick={() => {
                    axios.delete(`/api/tips/${tip.id}`).then(response => {
                        removeTip(tip.id);
                    });
                }}>{Lang.get('react.delete')}</button>
            </div>
        </div>
    </div>;
};

const TipItemMapping = {
    state: (state, props) => {
        let educationProgramType = null;
        // Try block because seems to be buggy :/
        try {
            if (props.tip.coupled_statistics.length > 0) {
                educationProgramType = state.entities.educationProgramTypes[
                    state.entities.statistics[
                        state.entities.coupledStatistics[props.tip.coupled_statistics[0]].statistic
                        ].education_program_type
                    ];
            }
        } catch (exception) {
            educationProgramType = null;
        }
        return {
            educationProgramType,
            ...props
        }
    },

    dispatch: (dispatch) => {
        return {
            removeTip: id => dispatch(actions.removeTip(id)),
        }
    }
};

const TipItemContainer = connect(TipItemMapping.state, TipItemMapping.dispatch)(tipItem);



