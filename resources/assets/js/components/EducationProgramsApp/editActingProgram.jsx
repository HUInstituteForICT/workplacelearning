import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import update from 'immutability-helper';

export default class EditActingProgram extends React.Component {

    constructor(props) {
        super(props);

        this.state = {loading: false, ep_name: '', competence: [], timeslot: [], resource_person:[]};

        this.programOnChange = this.programOnChange.bind(this);
        this.removeFromStateArray = this.removeFromStateArray.bind(this);
        this.onEntityCreated = this.onEntityCreated.bind(this);
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

    // On education program name change
    programOnChange(element) {
        this.setState({
            [element.target.getAttribute('name')]: element.target.value
        });
    }

    // On removing competence/timeslot/resourceperson from list
    removeFromStateArray(element) {
        const id = element.target.getAttribute('data-id');
        const type = element.target.getAttribute('data-type') === 'resourcePerson' ? 'resource_person' : element.target.getAttribute('data-type');

        EducationProgramService.deleteEntity(EntityTypes[type], id, response => {
            const index = this.state[type].findIndex(entity => entity[type + '_id'] === parseInt(id));
            this.setState(prevState => ({
                [type]: update(prevState[type], {$splice: [[index, 1]]})
            }));
        })
    }

    // When the user adds a new entity
    onEntityCreated(type, entity) {
        if (type === EntityTypes.competence) {
            this.setState(prevState => ({competence: update(prevState.competence, {$push: [entity]})}))
        } else if (type === EntityTypes.timeslot) {
            this.setState(prevState => ({timeslot: update(prevState.timeslot, {$push: [entity]})}))
        } else if(type === EntityTypes.resourcePerson) {
            this.setState(prevState => ({resource_person: update(prevState.resource_person, {$push: [entity]})}))
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
                               onChange={this.programOnChange}/>
                    </label>
                </div>
            </div>

            <div>
                <h4>Competencies</h4>
                <div className="form-group">
                    <ul>
                        {program.competence.map(competence => {
                            return <li key={competence.competence_id}>
                                <a data-type="competence" data-id={competence.competence_id}
                                   onClick={this.removeFromStateArray}>x</a> - {competence.competence_label}
                            </li>
                        })}
                    </ul>
                    <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.competence}
                                   programId={this.props.id}/>

                </div>
            </div>

            <div>
                <h4>Timeslots</h4>
                <div className="form-group">
                    <ul>
                        {program.timeslot.map(timeslot => {
                            return <li key={timeslot.timeslot_id}>
                                <a data-type="timeslot" data-id={timeslot.timeslot_id}
                                   onClick={this.removeFromStateArray}>x</a> - {timeslot.timeslot_text}
                            </li>
                        })}
                    </ul>
                    <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.timeslot}
                                   programId={this.props.id}/>

                </div>
            </div>

            <div>
                <h4>Resource Persons</h4>
                <div className="form-group">
                    <ul>
                        {program.resource_person.map(resourcePerson => {
                            return <li key={resourcePerson.rp_id}>
                                <a data-type="resourcePerson" data-id={resourcePerson.rp_id}
                                   onClick={this.removeFromStateArray}>x</a> - {resourcePerson.person_label}
                            </li>
                        })}
                    </ul>
                    <EntityCreator onEntityCreated={this.onEntityCreated} type={EntityTypes.resourcePerson}
                                   programId={this.props.id}/>

                </div>
            </div>
        </div>;
    }


}

const EntityTypes = {
    competence: 1,
    timeslot: 2,
    resourcePerson: 3,
    resource_person: 3 // because of inconsistent design necessary
};

class EntityCreator extends React.Component {


    constructor(props) {
        super(props);
        this.state = {fieldValue: ''};

        this.onFieldChange = this.onFieldChange.bind(this);
        this.onCreateEntityClick = this.onCreateEntityClick.bind(this);
    }

    onFieldChange(element) {
        this.setState({fieldValue: element.target.value});
    }

    onCreateEntityClick() {
        EducationProgramService.createEntity(
            this.props.programId,
            this.props.type,
            this.state.fieldValue,
            response => {
                this.props.onEntityCreated(this.props.type, response.data.entity)
            }
        );
    }

    render() {
        return <div className="row">
            <div className="col-md-6">
                <div className="form-group">

                    <input className="form-control" type="text" placeholder="name" onChange={this.onFieldChange}
                           value={this.state.fieldValue}/>
                    <br/>
                    <button className="btn" onClick={this.onCreateEntityClick}>Add</button>

                </div>
            </div>
        </div>
    }

}
