import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import EditProgram from "./EditProgram";
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

    onDeleteEducationProgram(id) {
        const index = this.state.programs.findIndex(program => parseInt(program.ep_id) === parseInt(id));
        if (index < 0) {
            throw "Unknown program id " + id;
        } else {
            this.setState({selectedProgramId: null});
            this.setState({programs: update(this.state.programs, {$splice: [[index, 1]]})});
        }

    }


    render() {
        if (this.state.loading) return <div className="loader">Loading...</div>;

        return <div>
            <div className="row">
                <div className="col-md-2">
                    <div className="panel panel-default">
                        <div className="panel-body">
                            <h4>{Lang.get('react.educationprogram')}</h4>
                            <p>{Lang.get('react.educprogram-manage')}</p>
                            {this.state.programs.map(programme => {

                                let buttonClass = 'defaultButton list';
                                if (this.state.selectedProgramId === programme.ep_id) {
                                    buttonClass += ' red'
                                }

                                return <span className={buttonClass} key={programme.ep_id}
                                             onClick={() => this.setState({selectedProgramId: programme.ep_id})}>{programme.ep_name}
                            </span>;
                            })}

                            <hr/>
                            {this.renderCreateForm()}
                        </div>
                    </div>
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
            <h5 style={{marginTop: 25}}>{Lang.get('react.educprogram-add')}</h5>
            <div className="form-group">

                <input className="form-control" type="text" value={this.state.newProgramName}
                       placeholder={Lang.get('react.educprogram-name')}
                       onChange={e => this.setState({newProgramName: e.target.value})}/>
                <br/>
                <select value={this.state.newProgramType}
                        onChange={this.onChangeEducationProgramType} className="form-control">
                    <option value="1">{Lang.get('react.acting')}</option>
                    <option value="2">{Lang.get('react.producing')}</option>
                </select>
                <br/>
                <span className="btn btn-success fill"
                      onClick={this.onClickAddEducationProgram}>{Lang.get('react.add')}</span>
            </div>
        </div>
    }

    renderEditForm() {

        if (this.state.selectedProgramId === null) {
            return <h5>{Lang.get('react.none-selected')}</h5>
        }

        return <EditProgram id={this.state.selectedProgramId}
                            programOnNameChange={this.updateProgramName}
                            onDelete={this.onDeleteEducationProgram.bind(this)}

        />;
    }

}