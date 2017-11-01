import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";

export const EntityTypes = {
    competence: "competencies",
    timeslot: "timeslots",
    resourcePerson: "resourcePersons",
    resource_person: "resourcePersons", // because of inconsistent design necessary
    category: "categories"
};

export class EntityCreator extends React.Component {

    constructor(props) {
        super(props);
        this.state = {fieldValue: ''};

        this.onFieldChange = this.onFieldChange.bind(this);
        this.onCreateEntityClick = this.onCreateEntityClick.bind(this);
        this.onKeyPress = this.onKeyPress.bind(this);
    }

    onFieldChange(element) {
        this.setState({fieldValue: element.target.value});
    }

    onCreateEntityClick() {
        EducationProgramService.createEntity(
            this.props.cohortId,
            this.props.type,
            this.state.fieldValue,
            response => {
                this.props.onEntityCreated(this.props.type, response.data.entity);
                this.setState({fieldValue: ''})
            }
        );
    }

    onKeyPress(event) {
        if(event.key === 'Enter') {
            this.onCreateEntityClick();
        }
    }

    render() {
        return <div style={{marginTop: '25px'}}>
                <div className="form-group">

                    <input onKeyPress={this.onKeyPress} className="form-control" type="text" placeholder="Name of item to add" onChange={this.onFieldChange}
                           value={this.state.fieldValue}/>
                    <br/>
                    <span className="defaultButton add" onClick={this.onCreateEntityClick}>{Lang.get('react.add')}</span>

                </div>
                <hr/>


        </div>
    }

}
