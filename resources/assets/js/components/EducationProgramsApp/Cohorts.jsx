import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import update from "immutability-helper";

import {Category, Competence, ResourcePerson, Timeslot} from "./EditableEntities";

export default class Cohorts extends React.Component {


    constructor(props) {
        super();
        this.state = Object.assign({}, props, {selectedCohortId: null, loading: false});

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
        EducationProgramService.cloneCohort(id, response => {
            const cohorts = this.state.cohorts.slice();
            cohorts.push(response.data);
            this.setState({cohorts: cohorts});
        })
    }

    render() {
        const selectedCohort = this.state.cohorts[this.cohortIndex(this.state.selectedCohortId)];
        return <div>

            <h3>{Lang.get('react.cohorts')}</h3>
            <a onClick={() => EducationProgramService.createCohort(this.props.programId, response => {
                const cohorts = this.state.cohorts.slice();
                cohorts.push(response.data);
                this.setState({cohorts: cohorts});
            })}>{Lang.get('react.add-cohort')}</a>
            <div className={"row"}>

                {this.state.cohorts.map(cohort => {
                    return <div className={"col-md-4"} key={cohort.id}>
                        <span className="defaultButton list"
                              onClick={() => this.setState({loading: true, selectedCohortId: cohort.id}, () => this.loadCohort(cohort.id))}>{cohort.name}
                        </span>
                    </div>;
                })}
            </div>
            <hr/>


            {(this.state.selectedCohortId !== null && this.state.loading === false) &&

            <div>
                <h4>{Lang.get('react.cohort-details')}</h4>
                <div className={"row"}>
                    <div className={"col-md-6"}>
                        <div className="form-group">
                            <label>
                                {Lang.get('react.cohort-name')}
                                <input type="text" className="form-control" value={selectedCohort.name}
                                       onChange={e => {
                                           this.setState(
                                               {
                                                   cohorts: update(this.state.cohorts, {
                                                       [this.cohortIndex(selectedCohort.id)]: {name: {$set: e.target.value}}
                                                   })

                                               });
                                           this.updateCohort();
                                       }}/>
                            </label>
                        </div>
                    </div>
                    <div className={"col-md-4"}>
                        <div className="form-group">
                            <label>
                                {Lang.get('react.cohort-desc')}
                                <input type="text" className="form-control" value={selectedCohort.description}
                                       onChange={e => {
                                           this.setState(
                                               {
                                                   cohorts: update(this.state.cohorts, {
                                                       [this.cohortIndex(selectedCohort.id)]: {description: {$set: e.target.value}}
                                                   })
                                               });
                                           this.updateCohort();
                                       }}/>
                            </label>
                        </div>
                    </div>
                    <div className="col-md-2">
                        <button onClick={() => this.cloneCohort(selectedCohort.id)} className="btn btn-success">{Lang.get('react.clone')} cohort</button>
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
                            {selectedCohort.disabled ? Lang.get('react.cohort-enable'):Lang.get('react.cohort-disable')}
                        </a>
                    </div>
                    <div className={"col-md-3"}>
                        <a disabled={selectedCohort.canBeDeleted} onClick={() => {
                            if(selectedCohort.canBeDeleted) {
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
            }


        </div>;
    }


}