import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import update from "immutability-helper";

import {Category, Competence, ResourcePerson, Timeslot} from "./EditableEntities";
import Card from "../Card";

export default class Cohorts extends React.Component {


    constructor(props) {
        super();
        this.state = Object.assign({}, props, {
            selectedCohortId: null,
            loading: false,
            showDisabledCohorts: false,
            isCloning: false
        });

        this.autoUpdaterTimeout = null;
    }

    cohortIndex(id) {
        return this.state.cohorts.findIndex(cohort => parseInt(cohort.id) === parseInt(id));
    }

    updateCohort() {
        clearTimeout(this.autoUpdaterTimeout);


        this.autoUpdaterTimeout = setTimeout(() => {
            EducationProgramService.updateCohort(this.state.selectedCohortId, this.state.cohorts[this.cohortIndex(this.state.selectedCohortId)], () => {
            })
        }, 500)
    }

    loadCohort(id) {
        this.setState({loading: true});
        EducationProgramService.loadCohort(id, response => {
            const index = this.cohortIndex(id);
            if (index >= 0) {
                this.setState({cohorts: update(this.state.cohorts, {[index]: {$set: response.data}})});
            } else {
                this.setState({cohorts: update(this.state.cohorts, {$push: [response.data]})});
            }
            this.setState({loading: false});
        });
    }

    cloneCohort(id) {
        this.setState({isCloning: true});
        EducationProgramService.cloneCohort(id, response => {
            const cohorts = this.state.cohorts.slice();
            cohorts.push(response.data);
            this.setState({cohorts: cohorts, isCloning: false});
        })
    }

    render() {
        const selectedCohort = this.state.cohorts[this.cohortIndex(this.state.selectedCohortId)];
        return <div>

            <h3>{Lang.get('react.cohorts')}</h3>
            <div className="row">
                <div className="col-md-2">
                    <a onClick={() => EducationProgramService.createCohort(this.props.programId, response => {
                        const cohorts = this.state.cohorts.slice();
                        cohorts.push(response.data);
                        this.setState({cohorts: cohorts});
                    })}>{Lang.get('react.add-cohort')}</a>
                </div>

                <div className="col-md-2 col-md-offset-8">
                    <a className="btn btn-info pull-right"
                       onClick={() => this.setState({showDisabledCohorts: !this.state.showDisabledCohorts})}>
                        {this.state.showDisabledCohorts && Lang.get('react.cohorts-hide-disabled')}
                        {!this.state.showDisabledCohorts && Lang.get('react.cohorts-show-disabled')}
                    </a>
                </div>

            </div>


            <div className={"row"}>

                {this.state.cohorts.filter(cohort => !cohort.disabled).map(cohort => {
                    let buttonClass = "defaultButton list ";
                    if (this.state.selectedCohortId === cohort.id) {
                        buttonClass += 'red';
                    }

                    return <div className={"col-md-4"} key={cohort.id}>
                        <span className={buttonClass}
                              onClick={() => this.setState({
                                  loading: true,
                                  selectedCohortId: cohort.id
                              }, () => this.loadCohort(cohort.id))}>{cohort.name}
                        </span>
                    </div>;
                })}

                {
                    this.state.showDisabledCohorts &&

                    this.state.cohorts.filter(cohort => cohort.disabled).map(cohort => {
                        let buttonClass = "defaultButton list ";
                        if (this.state.selectedCohortId === cohort.id) {
                            buttonClass += 'red';
                        } else {
                            buttonClass += 'yellow';
                        }

                        return <div className={"col-md-4"} key={cohort.id}>
                        <span className={buttonClass}
                              style={{opacity: '0.6'}}
                              onClick={() => this.setState({
                                  loading: true,
                                  selectedCohortId: cohort.id
                              }, () => this.loadCohort(cohort.id))}>{cohort.name}

                        </span>
                        </div>;
                    })
                }
            </div>
            <hr/>


            {(this.state.selectedCohortId !== null && this.state.loading === false) &&

            <div>
                <div className="row">
                    <div className="col-md-8">
                        <Card>
                            <h4>{Lang.get('react.cohort-details')}</h4>
                            <div className={"row"}>
                                <div className={"col-md-4"}>
                                    <div className="form-group">
                                        <label htmlFor="cohort-name">
                                            {Lang.get('react.cohort-name')}</label>
                                        <input name="cohort-name" type="text" className="form-control"
                                               value={selectedCohort.name}
                                               onChange={e => {
                                                   this.setState(
                                                       {
                                                           cohorts: update(this.state.cohorts, {
                                                               [this.cohortIndex(selectedCohort.id)]: {name: {$set: e.target.value}}
                                                           })
                                                       });
                                                   this.updateCohort();
                                               }}/>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="cohort-desc">{Lang.get('react.cohort-desc')}</label>
                                        <input name="cohort-desc" type="text" className="form-control"
                                               value={selectedCohort.description}
                                               onChange={e => {
                                                   this.setState(
                                                       {
                                                           cohorts: update(this.state.cohorts, {
                                                               [this.cohortIndex(selectedCohort.id)]: {description: {$set: e.target.value}}
                                                           })
                                                       });
                                                   this.updateCohort();
                                               }}/>

                                    </div>
                                </div>
                                <div className="col-md-2 col-md-offset-6 text-right">
                                    {this.state.isCloning && <button onClick={() => this.cloneCohort(selectedCohort.id)}
                                                                     className="btn btn-success">...
                                    </button>}
                                    {!this.state.isCloning &&
                                    <button onClick={() => this.cloneCohort(selectedCohort.id)}
                                            className="btn btn-success">{Lang.get('react.clone')} cohort
                                    </button>}
                                </div>
                            </div>
                            <div className={"row"}>
                                <div className={"col-md-3"}>
                                    <a onClick={() => EducationProgramService.toggleDisableCohort(selectedCohort.id, response => {
                                        if (response.data.status === "success") {
                                            const index = this.cohortIndex(selectedCohort.id);
                                            this.setState({cohorts: update(this.state.cohorts, {[index]: {disabled: {$set: response.data.disabled}}})});
                                        }
                                    })}>
                                        {selectedCohort.disabled ? Lang.get('react.cohort-enable') : Lang.get('react.cohort-disable')}
                                    </a>
                                </div>
                                <div className={"col-md-3"}>
                                    <a disabled={selectedCohort.canBeDeleted} onClick={() => {
                                        if (selectedCohort.canBeDeleted) {
                                            EducationProgramService.deleteCohort(selectedCohort.id, response => {
                                                if (response.data.status === "success") {
                                                    const index = this.cohortIndex(selectedCohort.id);
                                                    this.setState({selectedCohortId: null});
                                                    this.setState({cohorts: update(this.state.cohorts, {$splice: [[index, 1]]})});
                                                }
                                            })
                                        }
                                    }}>
                                        {selectedCohort.canBeDeleted ? Lang.get('react.cohort-delete') : Lang.get('react.cohort-delete-block')}
                                    </a>
                                </div>

                            </div>
                        </Card>
                    </div>
                </div>


                <br/>

                <div className="row">
                    {
                        this.props.programType === 1 &&
                        <div>
                            <Competence competencies={selectedCohort.competencies}
                                        competence_description={selectedCohort.competence_description}
                                        programId={this.props.programId} cohortId={this.state.selectedCohortId}
                            />
                            <Timeslot timeslots={selectedCohort.timeslots}
                                      programId={this.props.programId} cohortId={this.state.selectedCohortId}
                            />
                            <ResourcePerson resourcePersons={selectedCohort.resource_persons}
                                            programId={this.props.programId} cohortId={this.state.selectedCohortId}
                            />
                        </div>
                    }

                    {
                        this.props.programType === 2 &&
                        <div>
                            <Category categories={selectedCohort.categories}
                                      programId={this.props.programId} cohortId={this.state.selectedCohortId}
                            />
                            <ResourcePerson resourcePersons={selectedCohort.resource_persons}
                                            programId={this.props.programId} cohortId={this.state.selectedCohortId}
                            />
                        </div>

                    }
                </div>


            </div>
            }


        </div>;
    }


}