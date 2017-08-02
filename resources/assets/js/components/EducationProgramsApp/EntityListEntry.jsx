import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import {EntityTypes} from "./EntityCreator";

export default class EntityListEntry extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            editMode: false,
            fieldValue: '',
        };
        this.toEdit = this.toEdit.bind(this);
        this.save = this.save.bind(this);
        this.onChangeFieldValue = this.onChangeFieldValue.bind(this);
    }

    toEdit() {
        this.setState({
            editMode: true,
            fieldValue: this.props.label
        })
    }

    save() {
        this.setState({loading: true});

        EducationProgramService.updateEntity(this.props.id, {type: EntityTypes[this.props.type], name: this.state.fieldValue},
            response => {
                this.props.onEntityUpdatedName(this.props.id, this.props.type, this.state.fieldValue, response.data.mappedNameField);
                this.setState({loading:false, editMode:false});

            });

    }

    onChangeFieldValue(element) {
        this.setState({fieldValue: element.target.value});
    }

    render() {
        if (this.state.loading) return <div className="smallLoader"> </div>;

        // Render edit field
        if (this.state.editMode) {
            return <span>
                <input type="text" className="form-control" style={{width: '50%', display: 'inline-block'}} value={this.state.fieldValue} onChange={this.onChangeFieldValue}/>
                &nbsp;<a onClick={this.save}>save</a>
            </span>
        }

        // Render the default show field
        return <span>
            <a onClick={() => this.props.onRemoveClick(this.props.id, this.props.type)}>x</a> - {this.props.label} - <a
            onClick={this.toEdit}>edit</a>
        </span>
    }


}