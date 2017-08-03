import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";

export const EntityTypes = {
    competence: 1,
    timeslot: 2,
    resourcePerson: 3,
    resource_person: 3, // because of inconsistent design necessary
    category: 4
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
            this.props.programId,
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
                    <span className="defaultButton add" onClick={this.onCreateEntityClick}>Add</span>

                </div>
                <hr/>


        </div>
    }

}
