import * as React from "react";
import axios from "axios";

export default class CreateForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            operators: operators, // Loaded from a <script> tag declaration
            statisticVariables: statisticVariables, // Loaded from a <script> tag declaration

            name: '',
            statisticVariableOneIndex: 0,
            statisticVariableOneParameter: '',
            statisticVariableTwoIndex: 0,
            statisticVariableTwoParameter: '',
            operatorIndex: 0,

        }
    }

    statisticIndex(statisticToFind) {
        return this.state.statisticVariables.findIndex(statistic => statistic.name === statisticToFind.name);
    }

    operatorIndex(operatorToFind) {
        return this.state.operators.findIndex(operator => operator.type === operatorToFind.type);
    }

    submit() {
        if(!this.validate()) {
            return false;
        }

        const operator = this.state.operators[this.state.operatorIndex];
        const variableOne = this.state.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.state.statisticVariables[this.state.statisticVariableTwoIndex];

        axios.post('/statistics', {
            name: this.state.name,
            operator: operator.type,
            statisticVariableOne: variableOne,
            statisticVariableOneParameter: this.state.statisticVariableOneParameter,
            statisticVariableTwo: variableTwo,
            statisticVariableTwoParameter: this.state.statisticVariableTwoParameter
        }).then(response => {
            // window.location.href = "/statistics"
        }).catch(error => {
            console.log(error);
        });

    }

    validate() {
        if(this.state.name === '') {
            alert(Lang.get('react.statistic.errors.empty-name'));
            return false;
        }
        const operator = this.state.operators[this.state.operatorIndex];
        const variableOne = this.state.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.state.statisticVariables[this.state.statisticVariableTwoIndex];

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
            <h2>{Lang.get('react.statistic.create-statistic')}</h2>


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
                        {this.state.statisticVariables.map(
                            statisticVariable =>
                                <option key={statisticVariable.name}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name}
                                </option>)}
                    </select>

                    {
                        // Check if this Statistic Variable has a parameter
                        (this.state.statisticVariables[this.state.statisticVariableOneIndex].type === "collecteddatastatistic" &&
                            this.state.statisticVariables[this.state.statisticVariableOneIndex].hasParameters
                        ) && <div>
                            <strong>{Lang.get('react.statistic.parameter')}: {this.state.statisticVariables[this.state.statisticVariableOneIndex].parameterName}</strong>
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
                        {this.state.operators.map(
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
                        {this.state.statisticVariables.map(
                            statisticVariable =>
                                <option key={statisticVariable.name}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name}
                                </option>)}
                    </select>
                    {
                        // Check if this Statistic Variable has a parameter
                        (this.state.statisticVariables[this.state.statisticVariableTwoIndex].type === "collecteddatastatistic" &&
                            this.state.statisticVariables[this.state.statisticVariableTwoIndex].hasParameters
                        ) && <div>
                            <strong>{Lang.get('react.statistic.parameter')}: {this.state.statisticVariables[this.state.statisticVariableTwoIndex].parameterName}</strong>
                            <input value={this.state.statisticVariableTwoParameter}
                                   onChange={e => this.setState({statisticVariableTwoParameter: e.target.value})}
                                   type="text" className="form-control" maxLength={255}/>
                        </div>
                    }
                </div>


            </div>

            <a onClick={() => this.submit()}>hoi</a>

        </div>;
    }


}