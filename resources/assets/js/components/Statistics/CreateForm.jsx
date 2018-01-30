import * as React from "react";
import axios from "axios";

export default class CreateForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            name: '',
            statisticVariableOneIndex: 0,
            statisticVariableOneParameter: '',
            statisticVariableTwoIndex: 0,
            statisticVariableTwoParameter: '',
            operatorIndex: 0,

        }
    }

    statisticIndex(statisticToFind) {
        return this.props.statisticVariables.findIndex(statistic => statistic.name === statisticToFind.name);
    }

    operatorIndex(operatorToFind) {
        return this.props.operators.findIndex(operator => operator.type === operatorToFind.type);
    }

    submit() {
        if(!this.validate()) {
            return false;
        }

        const operator = this.props.operators[this.state.operatorIndex];
        const variableOne = this.props.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.props.statisticVariables[this.state.statisticVariableTwoIndex];

        axios.post('/api/statistics', {
            name: this.state.name,
            operator: operator.type,
            statisticVariableOne: variableOne,
            statisticVariableOneParameter: this.state.statisticVariableOneParameter,
            statisticVariableTwo: variableTwo,
            statisticVariableTwoParameter: this.state.statisticVariableTwoParameter,
            educationProgramTypeId: variableOne.education_program_type_id
        }).then(response => {
            this.props.onCreated(response.data);
        }).catch(error => {
            console.log(error);
        });

    }

    validate() {
        if(this.state.name === '') {
            alert(Lang.get('react.statistic.errors.empty-name'));
            return false;
        }
        const operator = this.props.operators[this.state.operatorIndex];
        const variableOne = this.props.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.props.statisticVariables[this.state.statisticVariableTwoIndex];

        if(operator.type < 0 || operator.type > 3) { // 4 operators: +-*/
            return false;
        }
        if(variableOne.type === "collecteddatastatistic" && variableOne.hasParameters && this.state.statisticVariableOneParameter === '') {
            alert(Lang.get('react.statistic.errors.empty-variable-parameter'));
            return false;
        }
        if(variableTwo.type === "collecteddatastatistic" && variableTwo.hasParameters && this.state.statisticVariableTwoParameter === '') {
            alert(Lang.get('react.statistic.errors.empty-variable-parameter'));
            return false;
        }

        return true;
    }

    render() {
        return <div>
            <div className="row">
                <div className="col-md-4">
                    <strong>{Lang.get('react.statistic.statistic-name')}</strong><br/>
                    <input onChange={e => this.setState({name: e.target.value})} value={this.state.name}
                           className="form-control" type="text" maxLength={255}/>
                </div>
            </div>


            <div className="row">

                <div className="col-md-4">

                    <h4>{Lang.get('react.statistic.select-variable-one')}</h4>
                    <select className="form-control" onChange={e => this.setState({
                        statisticVariableOneIndex: parseInt(e.target.value)
                    })} value={this.state.statisticVariableOneIndex}>
                        {this.props.statisticVariables.map(
                            statisticVariable =>
                                <option key={`${statisticVariable.id}`}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name} - ({this.props.educationProgramTypes[statisticVariable.education_program_type_id].eptype_name})
                                </option>)}
                    </select>

                    {
                        // Check if this Statistic Variable has a parameter
                        (this.props.statisticVariables[this.state.statisticVariableOneIndex].type === "collecteddatastatistic" &&
                            this.props.statisticVariables[this.state.statisticVariableOneIndex].hasParameters
                        ) && <div>
                            <strong>{Lang.get('react.statistic.parameter')}: {this.props.statisticVariables[this.state.statisticVariableOneIndex].parameterName}</strong>
                            <input value={this.state.statisticVariableOneParameter}
                                   onChange={e => this.setState({statisticVariableOneParameter: e.target.value})}
                                   type="text" className="form-control" maxLength={255}/>
                        </div>
                    }

                </div>

                <div className="col-md-4">
                    <h4>{Lang.get('react.statistic.select-operator')}</h4>
                    <select className="form-control" onChange={e => this.setState({
                        operatorIndex: parseInt(e.target.value)
                    })} value={this.state.operatorIndex}>
                        {this.props.operators.map(
                            operator =>
                                <option key={operator.label}
                                        value={this.operatorIndex(operator)}>
                                    {operator.label}
                                </option>)}
                    </select>
                </div>

                <div className="col-md-4">
                    <h4>{Lang.get('react.statistic.select-variable-two')}</h4>
                    <select className="form-control" onChange={e => this.setState({
                        statisticVariableTwoIndex: parseInt(e.target.value)
                    })} value={this.state.statisticVariableTwoIndex}>
                        {this.props.statisticVariables.map(
                            statisticVariable =>
                                <option key={`${statisticVariable.id}`}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name} - ({this.props.educationProgramTypes[statisticVariable.education_program_type_id].eptype_name})
                                </option>)}
                    </select>
                    {
                        // Check if this Statistic Variable has a parameter
                        (this.props.statisticVariables[this.state.statisticVariableTwoIndex].type === "collecteddatastatistic" &&
                            this.props.statisticVariables[this.state.statisticVariableTwoIndex].hasParameters
                        ) && <div>
                            <strong>{Lang.get('react.statistic.parameter')}: {this.props.statisticVariables[this.state.statisticVariableTwoIndex].parameterName}</strong>
                            <input value={this.state.statisticVariableTwoParameter}
                                   onChange={e => this.setState({statisticVariableTwoParameter: e.target.value})}
                                   type="text" className="form-control" maxLength={255}/>
                        </div>
                    }
                </div>
            </div>


            <a className="defaultButton" onClick={() => this.submit()}>{Lang.get('react.statistic.create')}</a>

        </div>;
    }


}