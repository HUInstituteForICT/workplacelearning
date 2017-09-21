import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import EditActingProgram from "./editActingProgram";
import EditProducingProgram from "./editProducingProgram";
import update from 'immutability-helper';


export default class educationProgramsApp extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            loading: false,
            programs: [],
            selectedProgramId: null,

            newProgramType: 1,
            newProgramName: '',
            newProgramLoading: false
        };

        this.updateProgramName = this.updateProgramName.bind(this);
        this.onChangeEducationProgramType = this.onChangeEducationProgramType.bind(this);
        this.onClickAddEducationProgram = this.onClickAddEducationProgram.bind(this);
    }

    componentDidMount() {
        this.setState({loading: true});

        EducationProgramService.getEducationPrograms(response => {
            this.setState({programs: response.data, loading: false})
        });
    }

    /**
     * Update the name shown in the list, called by a child component
     * @param id
     * @param name
     */
    updateProgramName(id, name) {
        let index = this.state.programs.map(function (program) {
            return program.ep_id;
        }).indexOf(id);
        this.setState({programs: update(this.state.programs, {[index]: {ep_name: {$set: name}}})});
    }

    onChangeEducationProgramType(e) {
        this.setState({newProgramType: e.target.value});
    }

    onClickAddEducationProgram(e) {
        this.setState({newProgramLoading: true});
        EducationProgramService.createEducationProgram({
                ep_name: this.state.newProgramName,
                eptype_id: this.state.newProgramType
            },
            response => {
                this.setState({
                    newProgramName: '',
                    newProgramLoading: false,
                    programs: update(this.state.programs, {$push: [response.data.program]})
                });
            });
    }


    render() {
        if (this.state.loading) return <div className="loader">Loading...</div>;

        return <div>
            <div className="row">
                <div className="col-md-2">
                    <h4>Education Program</h4>
                    <p>Manage and create education programs</p>
                    {this.state.programs.map(program => {
                        return <span className="defaultButton list" key={program.ep_id}
                                     onClick={() => this.setState({selectedProgramId: program.ep_id})}>{program.ep_name}
                            </span>;
                    })}

                    <hr/>
                    {this.renderCreateForm()}

                </div>

                <div className="col-md-10 ">
                    {this.renderEditForm()}
                </div>
            </div>

        </div>;
    }

    renderCreateForm() {
        if (this.state.newProgramLoading) return <div className="loader">Loading...</div>;

        return <div>
            <h5 style={{marginTop: 25}}>Add education programs</h5>
            <div className="form-group">

                <input className="form-control" type="text" value={this.state.newProgramName}
                       placeholder="Program name"
                       onChange={e => this.setState({newProgramName: e.target.value})}/>
                <br/>
                <select value={this.state.newProgramType}
                        onChange={this.onChangeEducationProgramType} className="form-control">
                    <option value="1">Acting</option>
                    <option value="2">Producing</option>
                </select>
                <br/>
                <span className="defaultButton fill" onClick={this.onClickAddEducationProgram}>Add</span>
            </div>
        </div>
    }

    renderEditForm() {

        if (this.state.selectedProgramId === null) {
            return <h5>None selected</h5>
        }
        const index = this.state.programs.findIndex(program => (program.ep_id === this.state.selectedProgramId));

        let program = this.state.programs[index];

        if (program.eptype_id === 1) {
            return <EditActingProgram id={this.state.selectedProgramId}
                                      programOnNameChange={this.updateProgramName}

            />
        } else if (program.eptype_id === 2) {
            return <EditProducingProgram id={this.state.selectedProgramId}
                                         programOnNameChange={this.updateProgramName}

            />
        } else {
            throw "Unknown education program type id :" + program.eptype_id;
        }


    }

}