import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import {EntityCreator, EntityTypes} from "./EntityCreator";
import EntityListEntry from "./EntityListEntry";
import update from 'immutability-helper';


export default class editProducingProgram extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            loading: false,
            ep_name: '',
            resource_person: [],
            category: []
        };

        this.autoUpdaterTimeout = null;

        this.programOnNameChange = this.programOnNameChange.bind(this);
        this.removeFromStateArray = this.removeFromStateArray.bind(this);
        this.onEntityCreated = this.onEntityCreated.bind(this);
        this.onEntityUpdatedName = this.onEntityUpdatedName.bind(this);
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
        } else if(type === EntityTypes.resourcePerson) {
            this.setState(prevState => ({resource_person: update(prevState.resource_person, {$push: [entity]})}))
        } else if(type === EntityTypes.category) {
            this.setState(prevState => ({category: update(prevState.category, {$push:[entity]})}));
        }
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
            </div>


            <div className="row">
                <div className="col-md-4">
                    <h4>Categories</h4>
                    <div className="form-group">
                        {program.category.map(category => {
                            return <div key={category.category_id}>
                                <EntityListEntry type="category"
                                                 id={category.category_id}
                                                 label={category.category_label}
                                                 onRemoveClick={this.removeFromStateArray}
                                                 onEntityUpdatedName={this.onEntityUpdatedName}

                                />

                            </div>
                        })}
                        <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.category}
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