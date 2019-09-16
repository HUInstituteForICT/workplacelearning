import * as React from "react";
import {EntityCreator, EntityTypes} from "./EntityCreator";
import EducationProgramService from "../../services/EducationProgramService";
import EntityListEntry from "./EntityListEntry";
import Dropzone from "react-dropzone";
import update from "immutability-helper";


class Entity extends React.Component {
    constructor(props) {
        super(props);
    }

    removeFromStateArray(id, type) {
        // Magic
        EducationProgramService.deleteEntity(type, id, response => {
            const index = this.getEntityIndex(id, type);
            this.setState(prevState => ({
                [EntityTypes[type]]: update(prevState[EntityTypes[type]], {$splice: [[index, 1]]})
            }));
        })
    }

    // Get the index of the entity with ID and type
    // Type defines which array in the state
    getEntityIndex(id, type) {
        let idPropertyName = type === 'resourcePerson' ? 'rp' : type;
        return this.state[EntityTypes[type]].findIndex(entity => entity[idPropertyName + '_id'] === parseInt(id));
    }

    // Update the entity in the parent array
    onEntityUpdatedName(id, type, name, mappedNameField) {
        const index = this.getEntityIndex(id, type);
        this.setState(prevState => ({
            [EntityTypes[type]]: update(prevState[EntityTypes[type]], {[index]: {[mappedNameField]: {$set: name}}})
        }));
    }

    // When the user adds a new entity
    onEntityCreated(type, entity) {
        if (type === "competence") {
            this.setState(prevState => ({competencies: update(prevState.competencies, {$push: [entity]})}))
        } else if (type === "timeslot") {
            this.setState(prevState => ({timeslots: update(prevState.timeslots, {$push: [entity]})}))
        } else if (type === "resourcePerson") {
            this.setState(prevState => ({resourcePersons: update(prevState.resourcePersons, {$push: [entity]})}))
        } else if (type === "category") {
            this.setState(prevState => ({categories: update(prevState.categories, {$push: [entity]})}))
        }
    }
}

class Competence extends Entity {
    constructor(props) {
        super(props);
        this.state = Object.assign({}, props, {uploadedText: '',});
    }

    // On dropping file in dropzone
    onDrop(files) {
        let reader = new FileReader();
        reader.addEventListener("load", () => {
            // Upload to server
            this.setState({uploadedText: ' - uploading...'});

            EducationProgramService.uploadCompetenceDescription(this.props.cohortId, reader.result,
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

    render() {
        return <div className="col-md-4">
            <div className="panel panel-default">
                <div className="panel-body">
                    <h4>{Lang.get('react.competencies')}</h4>
                    <div className="form-group">

                        {this.state.competencies.map(competence => {
                            return <div key={competence.competence_id}>
                                <EntityListEntry type="competence"
                                                 id={competence.competence_id}
                                                 label={competence.competence_label}
                                                 onRemoveClick={this.removeFromStateArray.bind(this)}
                                                 onEntityUpdatedName={this.onEntityUpdatedName.bind(this)}
                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated.bind(this)} type={"competence"}
                                       cohortId={this.props.cohortId}/>


                        <h5>{Lang.get('react.competence-description')}</h5>
                        <div>
                        <span>
                            {Lang.get('react.current-description')}:
                            &nbsp;
                            {this.state.competence_description !== null && this.state.competence_description.has_data &&
                            <span>
                                <a href={this.state.competence_description['download-url']}>{Lang.get('react.download')}</a>
                                &nbsp;-&nbsp;
                                <a onClick={() => {
                                    EducationProgramService.removeCompetenceDescription(this.props.programId, () => {
                                        this.setState({competence_description: null})
                                    });
                                }}>
                                {Lang.get('react.remove')}
                                </a>
                            </span>
                            }
                            {(this.state.competence_description === null || !this.state.competence_description.has_data) &&
                            <span>{Lang.get('react.none')}</span>
                            }
                            {this.state.uploadedText}
                        </span>
                            <Dropzone className="dropzone" accept="application/pdf" multiple={false}
                                      onDrop={this.onDrop.bind(this)}>
                                {({getRootProps, getInputProps}) => (
                                    <div {...getRootProps({className: 'dropzone'})}>
                                        <input {...getInputProps({value: ''})} />
                                         <span>
                                            {Lang.get('react.upload-instructions')}
                                        </span>
                                    </div>
                                )}
                            </Dropzone>
                        </div>
                    </div>
                </div>
            </div>
        </div>;
    }

}

class Timeslot extends Entity {
    constructor(props) {
        super(props);
        this.state = Object.assign({}, props);
    }

    render() {
        return <div className="col-md-4">
            <div className="panel panel-default">
                <div className="panel-body">
                    <h4>{Lang.get('react.categories')}</h4>
                    <div className="form-group">

                        {this.state.timeslots.map(timeslot => {
                            return <div key={timeslot.timeslot_id}>
                                <EntityListEntry type="timeslot"
                                                 id={timeslot.timeslot_id}
                                                 label={timeslot.timeslot_text}
                                                 onRemoveClick={this.removeFromStateArray.bind(this)}
                                                 onEntityUpdatedName={this.onEntityUpdatedName.bind(this)}
                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated.bind(this)} type={"timeslot"}
                                       cohortId={this.props.cohortId}/>
                    </div>
                </div>
            </div>
        </div>;
    }

}

class ResourcePerson extends Entity {
    constructor(props) {
        super(props);
        this.state = Object.assign({}, props);
    }

    render() {
        return <div className="col-md-4">
            <div className="panel panel-default">
                <div className="panel-body">
                    <h4>{Lang.get('react.resourceperson')}</h4>
                    <div className="form-group">

                        {this.state.resourcePersons.map(resourcePerson => {
                            return <div key={resourcePerson.rp_id}>
                                <EntityListEntry type="resourcePerson"
                                                 id={resourcePerson.rp_id}
                                                 label={resourcePerson.person_label}
                                                 onRemoveClick={this.removeFromStateArray.bind(this)}
                                                 onEntityUpdatedName={this.onEntityUpdatedName.bind(this)}
                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated.bind(this)} type={"resourcePerson"}
                                       cohortId={this.props.cohortId}/>
                    </div>
                </div>
            </div>
        </div>;
    }

}

class Category extends Entity {
    constructor(props) {
        super(props);
        this.state = Object.assign({}, props);
    }

    render() {
        return <div className="col-md-4">
            <div className="panel panel-default">
                <div className="panel-body">
                    <h4>{Lang.get('react.categories')}</h4>
                    <div className="form-group">

                        {this.state.categories.map(category => {
                            return <div key={category.category_id}>
                                <EntityListEntry type="category"
                                                 id={category.category_id}
                                                 label={category.category_label}
                                                 onRemoveClick={this.removeFromStateArray.bind(this)}
                                                 onEntityUpdatedName={this.onEntityUpdatedName.bind(this)}
                                />
                            </div>
                        })}

                        <EntityCreator onEntityCreated={this.onEntityCreated.bind(this)} type={"category"}
                                       cohortId={this.props.cohortId}/>
                    </div>
                </div>
            </div>
        </div>;
    }

}


export {Competence, Timeslot, ResourcePerson, Category};