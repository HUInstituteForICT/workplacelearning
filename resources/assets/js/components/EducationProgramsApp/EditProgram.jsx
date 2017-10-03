import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import update from 'immutability-helper';
import {EntityCreator, EntityTypes} from "./EntityCreator";
import EntityListEntry from "./EntityListEntry";
import Dropzone from "react-dropzone";
import * as EditableEntities from "./EditableEntities";
import Cohorts from "./Cohorts";

export default class EditProgram extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            ep_name: '',
            disabled: false,
            cohorts: []
        };

        this.autoUpdaterTimeout = null;

        this.programOnNameChange = this.programOnNameChange.bind(this);
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
                <div className={"row"}>

                    <div className={"col-md-3"}>
                        <a onClick={() => this.onClickToggleDisableProgram(this.props.id)}>
                            {program.disabled ? "Enable program for new students" : "Disable program for new students"}
                        </a>
                    </div>

                    <div className={"col-md-3"}>
                        <a disabled={program.canBeDeleted} onClick={() => {
                            if(program.canBeDeleted) {
                                EducationProgramService.deleteEducationProgram(program.ep_id, response => {
                                    if (response.data.status === "success") {
                                        this.props.onDelete(program.ep_id);
                                    }
                                })
                            }
                        }}>
                            {program.canBeDeleted ? "Delete program" : "Program has cohorts, therefore it cannot be deleted"}
                        </a>
                    </div>


                </div>

            </div>

            <hr/>

            <Cohorts programId={program.ep_id} cohorts={program.cohorts} programType={program.eptype_id}/>


        </div>;
    }


}