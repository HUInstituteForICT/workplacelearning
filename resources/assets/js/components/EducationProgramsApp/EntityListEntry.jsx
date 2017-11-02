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
        this.onKeyPress = this.onKeyPress.bind(this);
    }

    toEdit() {
        this.setState({
            editMode: true,
            fieldValue: this.props.label
        })
    }

    save() {
        this.setState({loading: true});

        EducationProgramService.updateEntity(this.props.id, {type: this.props.type, name: this.state.fieldValue},
            response => {
                this.props.onEntityUpdatedName(this.props.id, this.props.type, this.state.fieldValue, response.data.mappedNameField);
                this.setState({loading:false, editMode:false});

            });

    }

    onChangeFieldValue(element) {
        this.setState({fieldValue: element.target.value});
    }

    onKeyPress(event) {
        if (event.key === 'Enter') {
            this.save();
        }
    }

    render() {

        let styleBtn = {
            margin: '5px 0px',
            display: 'block'
        };

        // Render edit field
        if (this.state.editMode) {
            return <div className="buttonListItem expand">
                {this.state.loading && <div className="loader"/>}
                {!this.state.loading && <div className="">

                    <input onKeyPress={this.onKeyPress} type="text" className="form-control"
                           style={{display: 'inline-block', width: '100%'}} value={this.state.fieldValue}
                           onChange={this.onChangeFieldValue}/>
                    <br/>
                    <span className="defaultButton" style={styleBtn} onClick={this.save}>
                        {Lang.get('react.save')}
                        </span>
                    <span className="defaultButton red" style={styleBtn}
                          onClick={() => this.props.onRemoveClick(this.props.id, this.props.type)}>
                        {Lang.get('react.delete')}
                        </span>
                </div>}
            </div>
        }

        // Render the default show field
        return <div className="buttonListItem">
            <span className="defaultButton" onClick={this.toEdit}>
                {this.props.label}
            </span>
        </div>
    }


}