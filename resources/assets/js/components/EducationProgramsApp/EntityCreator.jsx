import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";

export const EntityTypes = {
    competence: 1,
    timeslot: 2,
    resourcePerson: 3,
    resource_person: 3 // because of inconsistent design necessary
};

export class EntityCreator extends React.Component {

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
                this.props.onEntityCreated(this.props.type, response.data.entity);
                this.setState({fieldValue: ''})
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
