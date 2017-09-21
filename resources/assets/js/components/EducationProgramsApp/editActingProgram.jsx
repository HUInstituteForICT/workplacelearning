import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import update from 'immutability-helper';
import {EntityCreator, EntityTypes} from "./EntityCreator";
import EntityListEntry from "./EntityListEntry";
import Dropzone from "react-dropzone";

export default class EditActingProgram extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            loading: false,
            ep_name: '',
            competence: [],
            competence_description: {id: null, has_data: false},
            uploadedText: '',
            timeslot: [],
            resource_person: [],
            disabled: false,
        };

        this.autoUpdaterTimeout = null;

        this.programOnNameChange = this.programOnNameChange.bind(this);
        this.removeFromStateArray = this.removeFromStateArray.bind(this);
        this.onEntityCreated = this.onEntityCreated.bind(this);
        this.onEntityUpdatedName = this.onEntityUpdatedName.bind(this);
        this.onDrop = this.onDrop.bind(this);
    }

    componentDidMount() {
        this.setState({loading: true});
        EducationProgramService.getEditableEducationProgram(response => {
            this.setState(response.data);
            this.setState({loading: false});

        }, this.props.id);
    }

    // Update component when the ID changes, fired by parent
    componentWillReceiveProps(nextProps) {
        if (nextProps.id !== this.props.id) {
            this.setState({loading: true});

            EducationProgramService.getEditableEducationProgram(response => {
                this.setState(response.data);
                this.setState({loading: false});
            }, nextProps.id);
        }
    }

    // On education program name change, update request fires 500ms after last keystroke
    programOnNameChange(element) {
        clearTimeout(this.autoUpdaterTimeout);
        this.setState({
            [element.target.getAttribute('name')]: element.target.value
        });

        this.autoUpdaterTimeout = setTimeout(() => {
            EducationProgramService.updateName(this.props.id, {ep_name: this.state.ep_name}, response => {
                this.props.programOnNameChange(this.props.id, response.data.program.ep_name);
            })
        }, 500)


    }

    // On removing competence/timeslot/resourceperson from list
    removeFromStateArray(id, type) {
        // Magic
        EducationProgramService.deleteEntity(EntityTypes[type], id, response => {
            const index = this.getEntityIndex(id, type);
            this.setState(prevState => ({
                [type]: update(prevState[type], {$splice: [[index, 1]]})
            }));
        })
    }

    // Get the index of the entity with ID and type
    // Type defines which array in the state
    getEntityIndex(id, type) {
        let idPropertyName = type === 'resource_person' ? 'rp' : type;
        return this.state[type].findIndex(entity => entity[idPropertyName + '_id'] === parseInt(id));
    }

    // Update the entity in the parent array
    onEntityUpdatedName(id, type, name, mappedNameField) {
        const index = this.getEntityIndex(id, type);
        this.setState(prevState => ({
            [type]: update(prevState[type], {[index]: {[mappedNameField]: {$set: name}}})
        }));
    }

    // When the user adds a new entity
    onEntityCreated(type, entity) {
        if (type === EntityTypes.competence) {
            this.setState(prevState => ({competence: update(prevState.competence, {$push: [entity]})}))
        } else if (type === EntityTypes.timeslot) {
            this.setState(prevState => ({timeslot: update(prevState.timeslot, {$push: [entity]})}))
        } else if (type === EntityTypes.resourcePerson) {
            this.setState(prevState => ({resource_person: update(prevState.resource_person, {$push: [entity]})}))
        }
    }

    // On dropping file in dropzone
    onDrop(files) {
        let reader = new FileReader();
        reader.addEventListener("load", () => {
            // Upload to server
            this.setState({uploadedText: ' - uploading...'});

            EducationProgramService.uploadCompetenceDescription(this.props.id, reader.result,
                response => {
                    this.setState({
                        competence_description: response.data.competence_description,
                        uploadedText: ' - successfully uploaded file'
                    });
                    setTimeout(() => this.setState({uploadedText: ''}), 4000);
                }, error => {
                    if (error.response.status === 413) {
                        this.setState({uploadedText: ' - the file was too large, try to make it smaller'});
                    } else {
                        this.setState({uploadedText: ' - error occurred while uploading, try again later'});
                    }
                });
        }, false);
        // Read file
        reader.readAsDataURL(files[0]);
    }

    onClickToggleDisableProgram(id) {
        EducationProgramService.toggleDisable(id, response => {
            if (response.data.status === "success") {
                this.setState({disabled: response.data.disabled});
            }
        })
    }

    render() {
        if (this.state.loading) return <div className="loader">Loading...</div>;
        const program = this.state;
        return <div>
            <div>
                <h4>Program details</h4>
                <div className="form-group">
                    <label>
                        Education program name
                        <input type="text" className="form-control" name="ep_name" value={program.ep_name}
                               onChange={this.programOnNameChange}/>
                    </label>
                </div>
                <a onClick={() => this.onClickToggleDisableProgram(this.props.id)}>
                    {program.disabled ? "Enable program for new students" : "Disable program for new students"}
                </a>

            </div>

            <hr/>

            <div className="row">
                <div className="col-md-4">
                    <h4>Competencies</h4>
                    <div className="form-group">

                        {program.competence.map(competence => {
                            return <div key={competence.competence_id}>
                                <EntityListEntry type="competence"
                                                 id={competence.competence_id}
                                                 label={competence.competence_label}
                                                 onRemoveClick={this.removeFromStateArray}
                                                 onEntityUpdatedName={this.onEntityUpdatedName}
                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.competence}
                                       programId={this.props.id}/>


                        <h5>Competence description</h5>
                        <div>
                        <span>
                            Current description:
                            &nbsp;
                            {this.state.competence_description !== null && this.state.competence_description.has_data &&
                            <span>
                                <a href={this.state.competence_description['download-url']}>download</a>
                                &nbsp;-&nbsp;
                                <a onClick={() => {
                                    EducationProgramService.removeCompetenceDescription(this.props.id, () => {
                                        this.setState({competence_description: null})
                                    });
                                }}>
                                remove
                                </a>
                            </span>
                            }
                            {(this.state.competence_description === null || !this.state.competence_description.has_data ) &&
                            <span>none</span>
                            }
                            {this.state.uploadedText}
                        </span>
                            <Dropzone className="dropzone" accept="application/pdf" multiple={false}
                                      onDrop={this.onDrop}>
                                <span>
                                    Click or drop file to upload the competence description
                                </span>
                            </Dropzone>
                        </div>
                    </div>
                </div>

                <div className="col-md-4">
                    <h4>Categories</h4>
                    <div className="form-group">

                        {program.timeslot.map(timeslot => {
                            return <div key={timeslot.timeslot_id}>
                                <EntityListEntry type="timeslot"
                                                 id={timeslot.timeslot_id}
                                                 label={timeslot.timeslot_text}
                                                 onRemoveClick={this.removeFromStateArray}
                                                 onEntityUpdatedName={this.onEntityUpdatedName}

                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.timeslot}
                                       programId={this.props.id}/>

                    </div>
                </div>

                <div className="col-md-4">
                    <h4>Resource Persons</h4>
                    <div className="form-group">
                        {program.resource_person.map(resourcePerson => {
                            return <div key={resourcePerson.rp_id}>
                                <EntityListEntry type="resource_person"
                                                 id={resourcePerson.rp_id}
                                                 label={resourcePerson.person_label}
                                                 onRemoveClick={this.removeFromStateArray}
                                                 onEntityUpdatedName={this.onEntityUpdatedName}

                                />

                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.resourcePerson}
                                       programId={this.props.id}/>

                    </div>
                </div>
            </div>


        </div>;
    }


}