import {connect} from "react-redux";
import React from "react";
import {Link} from "react-router-dom";
import {normalize} from "normalizr";
import {Schema} from "../../Schema";
import {actions as entityActions, actions} from "./redux/entities";
import axios from "axios";
import Modal from "react-responsive-modal";
import CreateForm from "../Statistics/CreateForm";
import UpdateForm from "../Statistics/UpdateForm";

class IndexPage extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            filterTips: '',
            filterStatistics: '',
            showTipDeleteModal: false,
            deleteTipId: null,
            deleteStatisticId: null,
            showStatisticDeleteModal: false,
            showNewStatisticModal: false,
            showUpdateStatisticModal: false,
            updateStatisticId: null,
        }
    }

    deleteTip = () => {
        axios.delete(`/api/tips/${this.state.deleteTipId}`).then(response => {
            this.props.removeTip(this.state.deleteTipId);
            this.setState({showTipDeleteModal: false, deleteTipId: null});
        });
    };

    deleteStatistic = () => {
        axios.delete(`/api/statistics/${this.state.deleteStatisticId}`).then(response => {
            this.props.removeStatistic(this.state.deleteStatisticId);
            this.setState({showStatisticDeleteModal: false, deleteStatisticId: null});
        });
    };

    createTip = (trigger) => {
        axios.post('/api/tips', {trigger}).then(response => {
            const normalized = normalize(response.data, Schema.tip);
            this.props.newTip(normalized.entities);
            this.props.history.push(`/tip/${normalized.result}`)
        })
    };

    render() {
        const {tips, statistics} = this.props;
        return <div>
            <Modal open={this.state.showTipDeleteModal} little
                   onClose={() => this.setState({showTipDeleteModal: false, deleteTipId: null})}
                   classNames={{'modal': "panel panel-default"}}>

                <div className="panel-body">
                    <p style={{padding: '20px'}}>{Lang.get('tips.delete-confirm')}</p>

                    <button onClick={this.deleteTip} className="btn btn-danger">{Lang.get('react.delete')}</button>&nbsp;
                    <button onClick={() => this.setState({showTipDeleteModal: false, deleteTipId: null})} className="btn btn-default">{Lang.get('react.cancel')}</button>

                </div>
            </Modal>
            <Modal open={this.state.showStatisticDeleteModal} little
                   onClose={() => this.setState({showStatisticDeleteModal: false, deleteStatisticId: null})}
                   classNames={{'modal': "panel panel-default"}}>

                <div className="panel-body">
                    <p style={{padding: '20px'}}>{Lang.get('statistics.delete-confirm')}</p>

                    <button onClick={this.deleteStatistic} className="btn btn-danger">{Lang.get('react.delete')}</button>&nbsp;
                    <button onClick={() => this.setState({showStatisticDeleteModal: false, deleteStatisticId: null})} className="btn btn-default">{Lang.get('react.cancel')}</button>

                </div>
            </Modal>
            <h1>Tips & statistieken</h1>

            <ul className="nav nav-tabs" role="tablist">
                <li className={this.props.currentPage === 'tips' ? 'active' : ''}><a onClick={e => this.props.setCurrentPage('tips')}
                                                                              style={{textTransform: 'capitalize'}}>{Lang.get('tips.tips')}</a>
                </li>
                <li className={this.props.currentPage === 'statistics' ? 'active' : ''}><a
                    onClick={e => this.props.setCurrentPage('statistics')}
                    style={{textTransform: 'capitalize'}}>{Lang.get('statistics.statistics')}</a></li>
                <li className={this.props.currentPage === 'help' ? 'active' : ''}><a
                    onClick={e => this.props.setCurrentPage('help')}
                    style={{textTransform: 'capitalize'}}>{Lang.get('tips.help.help')}</a></li>
            </ul>


            {
                this.props.currentPage === 'tips' &&

                <div>
                    <h3>{Lang.get('tips.tips')}</h3>
                    <button type="button" className="btn btn-primary" onClick={() => {this.createTip('statistic')}}>
                        {Lang.get('tips.new-statistic-driven')}
                    </button>&nbsp;
                    <button type="button" className="btn btn-primary" onClick={() => {this.createTip('moment')}}>
                        {Lang.get('tips.new-moment-driven')}
                    </button>
                    <br/><br/>

                    <div className="row">

                        <div className="col-lg-6">
                            <label htmlFor='tipsFilter'>Filter</label>
                            <input type="text" id='tipsFilter' value={this.state.filterTips}
                                   onChange={e => this.setState({filterTips: e.target.value})}
                                   className="form-control "/>
                        </div>
                    </div>

                    <table className="table">
                        <thead>
                        <tr>
                            <th>{Lang.get('react.tips.name')}</th>
                            <th>Trigger</th>
                            <th>Likes</th>
                            <th>{Lang.get('tips.views')}</th>
                            <th>{Lang.get('tips.active')}</th>
                            <th/>
                        </tr>
                        </thead>
                        <tbody>
                        {Object.values(tips)
                            .filter(tip => tip.name.toLowerCase().includes(this.state.filterTips.toLowerCase()))
                            .map(tip => <TipItemContainer onClickDelete={() => this.setState({showTipDeleteModal: true, deleteTipId: tip.id})} key={tip.id} tip={tip}/>)}
                        </tbody>
                    </table>

                </div>

            }

            {
                this.props.currentPage === 'statistics' &&
                <div>
                    <h3>{Lang.get('tips.statistics')}</h3>
                    <button type="button" className="btn btn-primary"
                            onClick={() => this.setState({showNewStatisticModal: true})}>
                        {Lang.get('statistics.create-new')}
                    </button>
                    &nbsp;
                    <br/><br/>
                    <div className="row">

                        <div className="col-lg-6">
                            <label htmlFor='statisticsFilter'>Filter</label>
                            <input type="text" id='statisticsFilter' value={this.state.filterStatistics}
                                   onChange={e => this.setState({filterStatistics: e.target.value})}
                                   className="form-control "/>
                        </div>
                    </div>

                    <table className="table">
                        <thead>
                        <tr>
                            <th>{Lang.get('react.tips.name')}</th>
                            <th>{Lang.get('react.tips.program')}</th>
                            <th/>
                        </tr>
                        </thead>
                        <tbody>
                        {Object.values(statistics)
                            .filter(statistic => statistic.name.toLowerCase().includes(this.state.filterStatistics.toLowerCase()))
                            .map(statistic => {
                            // If an id contains p-p- or p-a- it is not a statistic of a tip but rather a statistic that can be chosen for coupling to a tip, thus skip
                            // Also skip predefined statistics that have embodied a Statistic (they are but mere wrappers, no content)
                            if (String(statistic.id).includes(['p-p-', 'p-a-']) || statistic.type === 'predefinedstatistic') return null;
                            return <StatisticItemContainer key={statistic.id} statistic={statistic}
                                                           onClickDelete={() => this.setState({showStatisticDeleteModal: true, deleteStatisticId: statistic.id})}
                                                           onClickUpdate={() => this.setState({showUpdateStatisticModal: true, updateStatisticId: statistic.id})}
                            />
                        })}
                        </tbody>
                    </table>

                    <Modal open={this.state.showNewStatisticModal} little
                           onClose={() => this.setState({showNewStatisticModal: false})}
                           classNames={{'modal': "panel panel-default"}}>
                        <div className="panel-body" id="step-8">
                            <h3>{Lang.get('react.statistic.create-statistic')}</h3>
                            <CreateForm
                                onCreated={newEntity => {
                                    this.props.storeNewStatisticVariable(normalize(newEntity, Schema.statistic));
                                    this.setState({showNewStatisticModal: false});
                                }}
                            />
                        </div>
                    </Modal>

                    <Modal open={this.state.showUpdateStatisticModal} little
                           onClose={() => this.setState({showUpdateStatisticModal: false})}
                           classNames={{'modal': "panel panel-default"}}>
                        <div className="panel-body" id="step-8">
                            <h3>{Lang.get('statistics.edit')}</h3>
                            <UpdateForm id={this.state.updateStatisticId}/>
                        </div>
                    </Modal>
                </div>
            }

            {
                this.props.currentPage === 'help' && <div>

                    <h3>{Lang.get('tips.help.how-does-it-work')}</h3>

                    <strong>{Lang.get('tips.tips')}</strong>
                    <p>
                        {Lang.get('tips.help.explain-tips')}
                    </p>

                    <strong>{Lang.get('statistics.statistics')}</strong>
                    <p>
                        {Lang.get('tips.help.explain-statistics')}
                    </p>
                    <p>
                        {Lang.get('tips.help.explain-couple')}
                    </p>

                    <strong>{Lang.get('tips.moments')}</strong>
                    <p>
                        {Lang.get('tips.help.explain-moment')}
                    </p>


                    <strong>{Lang.get('tips.tiptext')}</strong>
                    <p>
                        {Lang.get('tips.help.explain-tiptext')}
                    </p>

                    <p>
                        {Lang.get('tips.help.explain-footer')}
                    </p>
                </div>
            }


        </div>
    }
}

const mapping = {
    state: state => ({
        entities: state.entities,
        tips: state.entities.tips,
        statistics: state.entities.statistics,
    }),
    dispatch: dispatch => ({
        newTip: entities => dispatch(actions.addEntities(entities)),
        removeTip: id => dispatch(actions.removeTip(id)),
        removeStatistic: id => dispatch(actions.removeStatistic(id)),
        storeNewStatisticVariable: normalized => {
            dispatch(entityActions.addEntities(normalized.entities));
        },
    })
};
export default connect(mapping.state, mapping.dispatch)(IndexPage);




const statisticItem = ({statistic, onClickDelete, onClickUpdate}) => {
    return <tr>
        <td>{statistic.name}</td>
        <td>{statistic.education_program_type || '-'}</td>
        <td>
            <button className="btn btn-primary" onClick={onClickUpdate}>{Lang.get('react.edit')}</button>
            &nbsp;&nbsp;&nbsp;
            <button className="btn btn-default" onClick={onClickDelete}>{Lang.get('react.delete')}</button>
        </td>
    </tr>;
};

const statisticItemMapping = {
    state: (state, props) => {
        return {
            ...props
        };
    },
    dispatch: (dispatch) => {
        return {

        };
    }
};

const StatisticItemContainer = connect(statisticItemMapping.state, statisticItemMapping.dispatch)(statisticItem);

const tipItem = ({tip, onClickDelete, coupledStatistics, statistics}) => {

    let hasActingCoupled, hasProducingCoupled = false;
    coupledStatistics.forEach(coupled => {
        const stat = statistics[coupled.statistic];
        if (stat.education_program_type === 'acting') hasActingCoupled = true;
        if (stat.education_program_type === 'producing') hasProducingCoupled = true;
    });

    return <tr>
        <td>{tip.name}</td>
        <td>
            {tip.trigger === 'moment' && Lang.get('tips.type-moment') }
            {tip.trigger === 'statistic' &&
            <span>{Lang.get('tips.type-statistic')} ({hasActingCoupled && 'acting'}{hasActingCoupled && hasProducingCoupled && ', '}{hasProducingCoupled && 'producing'})</span>}
        </td>
        <td>
            <span
                className="glyphicon glyphicon-thumbs-up"/>&nbsp;{tip.likes.filter(like => like.type === 1).length}&nbsp;/&nbsp;
            <span className="glyphicon glyphicon-thumbs-down"/>&nbsp;{tip.likes.filter(like => like.type === -1).length}
        </td>
        <td>
            {tip.student_tip_views.length}
        </td>
        <td>
            {tip.showInAnalysis && <span className="glyphicon glyphicon-ok" style={{color: 'green'}}/>}
            {!tip.showInAnalysis && <span className="glyphicon glyphicon-remove" style={{color:'red'}}/>}
        </td>
        <td>
            <Link to={`/tip/${tip.id}`}>
                <span className="btn btn-primary">{Lang.get('react.edit')}</span>
            </Link>
            &nbsp;&nbsp;&nbsp;
            <button className="btn btn-default" onClick={onClickDelete}>{Lang.get('react.delete')}</button>
        </td>
    </tr>;
};

const TipItemMapping = {
    state: (state, props) => {
        const tip = props.tip;
        return {
            ...props,
            coupledStatistics: tip.coupled_statistics.sort().map(id => state.entities.coupledStatistics[id]),
            statistics: state.entities.statistics,
        }
    },

    dispatch: (dispatch) => {
        return {
        }
    }
};

const TipItemContainer = connect(TipItemMapping.state, TipItemMapping.dispatch)(tipItem);



