import React from "react";
import Modal from "react-responsive-modal";
import CreateForm from "../Statistics/CreateForm";
import {normalize} from "normalizr";
import {Schema} from "../../Schema";
import {connect} from "react-redux";
import {actions as entityActions} from "./redux/entities";
import {actions as uiActions} from "./redux/tipPageUi";
import {actions as coupleStatisticActions} from "./redux/coupleStatistic";
import axios from "axios";
import CoupledStatisticItem from "./CoupledStatisticItem";

class CoupledStatistics extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showCoupleStatisticModal: false,
            coupleRequestBusy: false,
            showNewStatisticModal: false
        }
    }

    static getDerivedStateFromProps(props, state) {
        if (props.stepIndex === 2) {
            state.showCoupleStatisticModal = true;
        }

        if (props.stepIndex === 3) {
            state.showNewStatisticModal = true;
        }

        if (props.stepIndex === 11) {
            state.showNewStatisticModal = false;
        }

        if (props.stepIndex === 15) {
            state.showCoupleStatisticModal = false;
        }

        return state;
    }

    toggleCoupleModal = () => {
        this.setState({showCoupleStatisticModal: !this.state.showCoupleStatisticModal});
        if (this.props.runJoyride && this.props.stepIndex === 1) {
            this.props.setStepIndex({stepIndex: 2});
        }
    };

    toggleNewStatisticModal = () => {
        this.setState({showNewStatisticModal: !this.state.showNewStatisticModal});
        if (this.props.runJoyride && this.props.stepIndex === 2) {
            this.props.setStepIndex({stepIndex: 3});
        }
    };

    coupleStatistic = () => {
        const {tip, coupleStatisticForm, statistics, storeNewCoupledStatistic} = this.props;
        this.setState({coupleRequestBusy: true});
        axios.post('/api/tip-coupled-statistics', {
            tip_id: tip.id,
            statistic_id: coupleStatisticForm.statistic,
            threshold: coupleStatisticForm.threshold,
            className: statistics[coupleStatisticForm.statistic].className,
            comparisonOperator: coupleStatisticForm.comparisonOperator,

        }).then(response => {
            storeNewCoupledStatistic(normalize(response.data, Schema.coupledStatistic));
            if (this.props.joyRide) {
                this.props.joyRideRef.helpers.next();
            }
            this.setState({showCoupleStatisticModal: false, coupleRequestBusy: false});
        })
    };


    render() {
        // Check if user has two different Educ types statistics coupled, should be discouraged
        let hasActingCoupled, hasProducingCoupled = false;
        this.props.coupledStatistics.forEach(coupled => {
            const stat = this.props.statistics[coupled.statistic];
            if (stat.education_program_type === 'acting') hasActingCoupled = true;
            if (stat.education_program_type === 'producing') hasProducingCoupled = true;
        });


        return <div>
            <h3>{Lang.get('tips.coupled-statistics')}</h3>

            <button id="step-2" className="btn btn-primary"
                    onClick={this.toggleCoupleModal}>{Lang.get('statistics.couple')}
            </button>
            <br/><br/>

            <div className="row" style={{background: 'white', marginBottom: '10px', marginTop: '10px'}}
                 id="step-16">
                {hasProducingCoupled && hasActingCoupled &&
                <div className="alert alert-danger text-danger" role="alert">
                    {Lang.get('statistics.acting-producing-coupled')}
                </div>}
                <div>
                    {
                        this.props.coupledStatistics.map(coupledStatistic => {
                            const statistic = this.props.statistics[coupledStatistic.statistic];

                            return <div className="col-md-4" key={coupledStatistic.id}><CoupledStatisticItem
                                coupledStatistic={coupledStatistic}
                                tip={this.props.tip}
                                statistic={statistic}
                                educationProgramType={this.props.educationProgramTypes[statistic.education_program_type]}
                                editMode={this.props.coupledStatisticsInEditMode.includes(coupledStatistic.id)}
                                updateEntity={this.props.updateEntity}
                                toggleEditModeForCoupledStatistic={this.props.toggleEditModeForCoupledStatistic}
                                decoupleStatistic={this.props.decoupleStatistic}
                            /></div>
                        })
                    }
                </div>

            </div>


            <Modal open={this.state.showCoupleStatisticModal} center
                   onClose={() => this.setState({showCoupleStatisticModal: false})}
                   classNames={{'modal': "panel panel-default"}}>
                <div className="panel-body">
                    <h3 style={{display: 'inline-block'}}>{Lang.get('tips.couple-statistic')}</h3>
                    <br/>


                    <strong>{Lang.get('statistics.select-statistic')}</strong>
                    <div className="row">
                        <div className="col-lg-6" id="step-12">
                            <select value={this.props.coupleStatisticForm.statistic} className="form-control"
                                    onChange={e => this.props.updateCoupleStatisticFormProperty('statistic', e.target.value)}>
                                <option disabled={true}/>
                                {

                                    allowedStatistics(this.props.tip, this.props.statistics, this.props.coupledStatistics).map(
                                        statistic => <option key={statistic.id}
                                                             value={statistic.id}>

                                            {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                                            {statistic.type !== 'predefinedstatistic' && statistic.name}

                                            &nbsp;-&nbsp;
                                            ({statistic.education_program_type})
                                            {statistic.type === 'predefinedstatistic' && ' - (' + Lang.get('statistics.predefined') + ')'}
                                        </option>
                                    )
                                }
                            </select>
                        </div>
                    </div>

                    <br/>

                    <strong>{Lang.get('statistics.when-active')}</strong>
                    <div className="row">
                        <div className="col-lg-3" id="step-13">
                            <select value={this.props.coupleStatisticForm.comparisonOperator} className="form-control"
                                    onChange={e => this.props.updateCoupleStatisticFormProperty('comparisonOperator', e.target.value)}>
                                <option value="1">{Lang.get('statistics.greater-than')}</option>
                                <option value="0">{Lang.get('statistics.less-than')}</option>
                            </select>
                        </div>
                        <div className="col-lg-3" id="step-14">
                            <input type="number" className="form-control" step="any"
                                   value={this.props.coupleStatisticForm.threshold}
                                   onChange={e => this.props.updateCoupleStatisticFormProperty('threshold', e.target.value)}/>
                        </div>
                    </div>

                    <br/><br/>

                    <div>
                        <button className="btn btn-primary" id="step-15"
                                disabled={this.props.coupleStatisticForm.statistic === '' || this.props.coupleStatisticForm.threshold === '' || this.props.coupleStatisticForm.comparisonOperator > '2' || this.state.coupleRequestBusy}
                                onClick={this.coupleStatistic}>
                            {this.state.coupleRequestBusy &&
                            <span className="glyphicon glyphicon-refresh glyphicon-refresh-animate"/>}
                            {!this.state.coupleRequestBusy && <span>{Lang.get('statistics.couple')}</span>}

                        </button>

                        &nbsp;

                        <button className="btn btn-danger"
                                onClick={() => this.setState({showCoupleStatisticModal: false})}>
                            {Lang.get('statistics.cancel')}
                        </button>

                        <button className="pull-right btn btn-default" id="step-3"
                                onClick={this.toggleNewStatisticModal}>{Lang.get('react.statistic.create-statistic')}
                        </button>
                    </div>
                </div>
            </Modal>

            <Modal open={this.state.showNewStatisticModal} center
                   onClose={() => this.setState({showNewStatisticModal: false})}
                   classNames={{'modal': "panel panel-default"}}>
                <div className="panel-body" id="step-8">
                    <h3>{Lang.get('react.statistic.create-statistic')}</h3>
                    <CreateForm
                        joyrideStepIndex={this.props.stepIndex}
                        onCreated={newEntity => {
                            this.props.storeNewStatisticVariable(normalize(newEntity, Schema.statistic));
                            if (this.props.joyRide) {
                                this.props.joyRideRef.helpers.next();
                            }
                            this.setState({showNewStatisticModal: false});
                        }}
                    />
                </div>
            </Modal>
        </div>
    }
}


const mapping = {
    state: state => {
        return {
            educationProgramTypes: state.entities.educationProgramTypes,
            coupledStatisticsInEditMode: state.tipEditPageUi.inEditMode,
            coupleStatisticForm: state.coupleStatistic,
        }
    },
    dispatch: dispatch => {
        return {
            updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
            toggleEditModeForCoupledStatistic: id => dispatch(uiActions.toggleEditModeCoupledStatistic(id)),
            decoupleStatistic: coupledStatistic => dispatch(entityActions.decoupleStatisticFromTip(coupledStatistic)),
            updateCoupleStatisticFormProperty: (property, value) => dispatch(coupleStatisticActions.updateCoupleStatisticFormProperty(property, value)),
            storeNewCoupledStatistic: normalized => {

                dispatch(entityActions.addEntities(normalized.entities));
                dispatch(entityActions.addCoupledStatisticToTip(normalized.result, normalized.entities.coupledStatistics[normalized.result].tip_id));
            },
            storeNewStatisticVariable: normalized => {
                dispatch(entityActions.addEntities(normalized.entities));
            },
        }
    }
};

/**
 * Get the selectable statistics for this tip.
 */
const allowedStatistics = (tip, statistics) => Object.values(statistics)
    .filter(statistic =>
        String(statistic.id).startsWith('p-p-') ||
        String(statistic.id).startsWith('p-a-') ||
        statistic.type === 'customstatistic');


export default connect(mapping.state, mapping.dispatch)(CoupledStatistics);
