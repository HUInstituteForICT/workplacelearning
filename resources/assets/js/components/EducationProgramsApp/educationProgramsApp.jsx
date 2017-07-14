import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";
import EditActingProgram from "./editActingProgram";

export default class educationProgramsApp extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            loading: false,
            programs: [],
            selectedProgramId: null
        }
    }

    componentDidMount() {
        this.setState({loading:true});

        EducationProgramService.getEducationPrograms(response => {
            this.setState({programs: response.data, loading: false})
        });
    }

    render() {
        if(this.state.loading) return <div className="loader">Loading...</div>;

        return <div>

            <div className="row">
                <div className="col-md-3">
                    <h4>Education Program</h4>
                    <p>Manage and create education programs</p>
                    <ul>
                        {this.state.programs.map(program => {
                            return <li key={program.ep_id}>
                                {program.ep_name} - <a
                                onClick={() => this.setState({selectedProgramId: program.ep_id})}>edit</a>
                            </li>;
                        })}
                    </ul>
                </div>
                <div className="col-md-8 col-md-offset-1">
                    {this.renderEditForm()}
                </div>
            </div>

        </div>;
    }

    renderEditForm() {
        if(this.state.selectedProgramId === null) {
            return <h5>None selected</h5>
        }

        return <EditActingProgram id={this.state.selectedProgramId}/>

    }

}