import {connect} from "react-redux";
import React from "react";
import {actions as entityActions} from "./redux/entities";
import axios from "axios/index";
import {Link} from "react-router-dom";
import Joyride from "react-joyride";
import {ACTIONS, EVENTS} from 'react-joyride/es/constants';
import CoupledStatistics from "./CoupledStatistics";
import Moments from "./Moments";


class TipEditPage extends React.Component {

    joyrideRef = null;

    constructor(props) {
        super(props);
        this.state = {
            submitting: false,
            saveButtonText: Lang.get('statistics.couple'),
            runJoyride: false,
            stepIndex: 0,
            selectedEducationProgramId: null,

        }

    }

    toggleVisibleCohorts = (enable) => {
        const visibleCohorts = Object.values(this.props.cohorts).filter(cohort => this.state.selectedEducationProgramId === cohort.ep_id || this.state.selectedEducationProgramId === null);

        const enabledCohorts = [...this.props.tip.enabled_cohorts];


        visibleCohorts.forEach(cohort => {
            if (enabledCohorts.includes(cohort.id)) {
                if (!enable) {
                    enabledCohorts.splice(enabledCohorts.indexOf(cohort.id), 1);
                }
            } else {
                if (enable) {
                    enabledCohorts.push(cohort.id);
                }
            }
        });

        this.props.updateEntity('tips', this.props.tip.id, {
            ...this.props.tip,
            enabled_cohorts: enabledCohorts
        });
    };

    joyrideCallback = (tour) => {
        const {action, index, type} = tour;

        // Check if we need to open or close a modal, depending on which step we are
        if(type === EVENTS.STEP_AFTER) {
            this.setState({stepIndex: index + (action === ACTIONS.PREV ? -1 : 1)});
        }

        if(type === EVENTS.TOUR_END) {
            this.setState({runJoyride: false, stepIndex: 0});
        }

        this.forceUpdate();
    };

    // The steps of tour guide
    joyrideStepsStatisticTip = [
        {
            content: Lang.get('tips.help-steps.1'),
            target: "#step-1",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.2'),
            target: "#step-2",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.3'),
            target: "#step-3",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.4'),
            target: "#step-4",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.5'),
            target: "#step-5",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.6'),
            target: "#step-6",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.7'),
            target: "#step-7-and-8",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.8'),
            target: "#step-7-and-8",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.9'),
            target: "#step-9",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.10'),
            target: "#step-10",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.11'),
            target: "#step-11",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.12'),
            target: "#step-12",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.13'),
            target: "#step-13",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.14'),
            target: "#step-14",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.15'),
            target: "#step-15",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.16'),
            target: "#step-16",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.17'),
            target: "#step-17",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.18'),
            target: "#step-18",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.19'),
            target: "#step-19",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.20'),
            target: "#step-20",
            disableBeacon: true,
        },
    ];

    setStepIndex = stepIndex => this.setState({stepIndex});





    render = () => {
        const {
            match,
            tip, coupledStatistics,
            statistics, educationPrograms,
            cohorts, updateEntity, moments,
        } = this.props;

        if (tip === undefined) return <div>Loading...</div>;


        return <div>


            <div className="container">
            <Link to="/">
                <button type="button" className="btn">{Lang.get('tips.back')}</button>
            </Link>
                <button onClick={() => this.setState({runJoyride: true, stepIndex: 0})}
                        className="btn btn-danger pull-right">{Lang.get('tips.help-steps.guide')}</button>
            <br/>
            <h1>{Lang.get('tips.edit')}</h1>
                <div className="row">
                    <div className="col-lg-6">
                <input id="step-1" type="text" className="form-control" placeholder={Lang.get('tips.name')}
                       value={tip.name}
                       onChange={e => updateEntity('tips', tip.id, {...tip, name: e.target.value})}/>
                    </div>
            </div>


                <div>
                    {/* The list op coupled statistics for this tip */}

                    <CoupledStatistics
                        setStepIndex={this.setStepIndex}
                        joyRide={this.state.runJoyride}
                        joyRideRef={this.joyrideRef}
                        stepIndex={this.state.stepIndex}
                        tip={tip}
                        statistics={statistics}
                        coupledStatistics={coupledStatistics}
                    />

                </div>


                <div id='step-17' style={{backgroundColor: 'white'}}>
                    <Moments
                        tip={tip}
                        setStepIndex={this.setStepIndex}
                        joyRide={this.state.runJoyride}
                        joyRideRef={this.joyrideRef}
                        stepIndex={this.state.stepIndex}
                    />


                </div>

                <hr/>

                <div className="panel panel-default" id='step-18' style={{margin: '50px 0'}}>
                    <div className="panel-body">
                        <div className="form-group">
                            <label>{Lang.get('tips.tiptext')}</label>
                            <textarea className="form-control" value={tip.tipText} maxLength={1000} rows={3}
                                      onChange={e => updateEntity('tips', tip.id, {...tip, tipText: e.target.value})}/>
                        </div>
                        {coupledStatistics.length > 0 && <p>{Lang.get('tips.form.statistic-value-parameters')}</p>}
                        {tip.moments.length > 0 && <p>{Lang.get('tips.form.moment-value-parameters')}</p>}


                        <table className="table">
                            <thead>
                            <tr>
                                <th>{Lang.get('tips.form.table-statistic')}</th>
                                <th>{Lang.get('tips.form.table-value-parameter')}</th>
                                <th>{Lang.get('tips.form.table-value-name-parameter')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {tip.moments.length > 0 && <tr>
                                <td>{moments.map(moment => `(${moment.rangeStart}% - ${moment.rangeEnd}%)`).join(` ${Lang.get('tips.or')} `)}</td>
                                <td><strong>:days-percentage</strong></td>
                                <td/>
                            </tr>}
                            {
                                coupledStatistics.map(coupledStatistic => {

                                    const statistic = statistics[coupledStatistic.statistic];

                                    return <tr key={coupledStatistic.id}>
                                        <td>
                                            {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                                            {statistic.type !== 'predefinedstatistic' && statistic.name}
                                        </td>
                                        <td><strong>:statistic-{coupledStatistic.id}</strong></td>
                                        <td>
                                            {statistic.type === 'predefinedstatistic' &&
                                            <span>
                                                <strong>:statistic-name-{coupledStatistic.id}</strong>
                                                <br/>
                                                {statistic.type === 'predefinedstatistic' && statistic.valueParameterDescription}
                                            </span>
                                            }
                                        </td>
                                    </tr>
                                })
                            }
                            </tbody>
                        </table>

                    </div>
                </div>
            <hr/>

                <div className="form-group" style={{background: 'white'}} id="step-19">
                    <input id='analysisvisible' type="checkbox" checked={tip.showInAnalysis}
                       onChange={e => updateEntity('tips', tip.id, {...tip, showInAnalysis: e.target.checked})}/>
                    <label htmlFor='analysisvisible'>&nbsp;{Lang.get('tips.form.showInAnalysis')}</label>
                </div>

            <hr/>

            <h3>{Lang.get('tips.form.cohorts-enable')}</h3>

                <div>
                    <div className="row" style={{background: 'white', marginBottom: '20px'}} id="step-20">
                        <div className="col-lg-2">
                            <h5>{Lang.get('tips.education-programs')}</h5>
                            <button
                                className={'btn ' + (this.state.selectedEducationProgramId === null ? 'btn-primary' : 'btn-default')}
                                style={{width: '100%', marginTop: '5px', marginBottom: '5px'}}
                                type='button'
                                onClick={e => this.setState({selectedEducationProgramId: null})}
                            >{Lang.get('tips.education-programs-all')}</button>
                            {Object.values(educationPrograms).map(educationProgram => <button
                                key={educationProgram.ep_id}
                                className={'btn ' + (this.state.selectedEducationProgramId === educationProgram.ep_id ? 'btn-primary' : 'btn-default')}
                                style={{width: '100%', marginTop: '5px', marginBottom: '5px', whiteSpace: 'normal'}}
                                type='button'
                                onClick={e => this.setState({selectedEducationProgramId: educationProgram.ep_id})}
                            >{educationProgram.ep_name}</button>)}
                        </div>

                        <div className="col-lg-10">
                            <div className="row" style={{paddingBottom: '20px'}}>
                                <div className="col-lg-6">
                                    <label>{Lang.get('tips.form.enabledCohorts')}</label>
                                </div>
                                <div className="col-lg-6 text-right">
                                    <div className="btn-group">
                                        <button onClick={e => this.toggleVisibleCohorts(true)} type='button'
                                                className='btn btn-success'><span
                                            className='glyphicon glyphicon-ok'/>&nbsp;{Lang.get('tips.form.cohorts-enable-all')}
                                        </button>
                                        <button onClick={e => this.toggleVisibleCohorts(false)} type='button'
                                                className='btn btn-danger'><span
                                            className='glyphicon glyphicon-remove'/>&nbsp;{Lang.get('tips.form.cohorts-disable-all')}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div className="row">
                                {Object.values(cohorts)
                                    .filter(cohort => this.state.selectedEducationProgramId === cohort.ep_id || this.state.selectedEducationProgramId === null)
                                    .sort()
                                    .map(cohort => {
                                    return <div className="col-md-2" key={cohort.id}>
                                        <div className="form-group"><p className="checkbox">
                                            <label><input type="checkbox"
                                                          checked={tip.enabled_cohorts.includes(cohort.id)}
                                                          onChange={e => {
                                                              const enabled = [...tip.enabled_cohorts];
                                                              if (enabled.includes(cohort.id)) {
                                                                  enabled.splice(enabled.indexOf(cohort.id), 1);
                                                              } else {
                                                                  enabled.push(cohort.id);
                                                              }
                                                              updateEntity('tips', tip.id, {
                                                                  ...tip,
                                                                  enabled_cohorts: enabled
                                                              });
                                                          }}
                                            /> {cohort.name}
                                            </label></p>
                                        </div>
                                    </div>;
                                })}
                            </div>
                        </div>
                    </div>


                    <div style={{background: 'white'}} id="step-21">
                        <button disabled={tip.name === '' || tip.tipText === '' || this.state.submitting}
                                className="btn btn-primary" onClick={this.save}>
                            {this.state.submitting &&
                            <span className="glyphicon glyphicon-refresh glyphicon-refresh-animate"/>}
                            {!this.state.submitting && <span>{Lang.get('tips.save')}</span>}

                        </button>
                    </div>
                </div>

        </div>
            <Joyride
                ref={ref => {
                    this.joyrideRef = ref
                }}
                steps={this.joyrideStepsStatisticTip}
                run={this.state.runJoyride}
                hideBackButton={true}
                continuous={true}
                spotlightClicks={true}
                spotlightPadding={5}
                showProgress
                stepIndex={this.state.stepIndex}
                callback={this.joyrideCallback}
                styles={{
                    options: {zIndex: 99999}
                }}
                locale={{ back: Lang.get('tips.help-steps.back'), close: Lang.get('tips.help-steps.close'), last: Lang.get('tips.help-steps.last'), next: Lang.get('tips.help-steps.next'), skip: Lang.get('tips.help-steps.skip') }}
            />
        </div>
    };

    save = () => {
        const {tip} = this.props;
        this.setState({submitting: true});
        axios.put(`/api/tips/${tip.id}`, tip).then(response => {
            this.setState({submitting: false,});
        });
    }

}


const mapping = {
    state: (state, props) => {
        const tip = state.entities.tips[props.match.params.id];
        // Signal whether we're loading
        if (tip === undefined) return {tip: undefined};
        const coupledStatistics = tip.coupled_statistics.sort().map(id => state.entities.coupledStatistics[id]);
        return {
            tip,
            coupledStatistics,
            statistics: state.entities.statistics,
            educationProgramTypes: state.entities.educationProgramTypes,
            educationPrograms: state.entities.educationPrograms,
            variableFilters: state.tipEditPageUi.variableFilters,
            cohorts: state.entities.cohorts,
            coupleStatisticForm: state.coupleStatistic,
            coupledStatisticsInEditMode: state.tipEditPageUi.inEditMode,
            moments: tip.moments.map(id => state.entities.moments[id]),
        };
    },
    dispatch: dispatch => ({
        updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
    })
};

export default connect(mapping.state, mapping.dispatch)(TipEditPage);


const coupleStatisticForms = () => {

};