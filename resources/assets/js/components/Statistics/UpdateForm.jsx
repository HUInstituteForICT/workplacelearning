import * as React from "react";
import axios from "axios";
import {connect} from "react-redux";
import {Schema} from "../../Schema";
import {normalize} from "normalizr";
import {actions} from "../Tips/redux/entities";
import {Link, withRouter} from "react-router-dom";

class UpdateForm extends React.Component {

    constructor(props) {
        super(props);
        if (props.loading) {
            this.state = {};
            return;
        }
        this.state = {
            name: props.source.name,
            statisticVariableOneIndex: props.source.statisticVariableOneIndex,
            statisticVariableOneParameter: props.source.statisticVariableOneParameter,
            statisticVariableTwoIndex: props.source.statisticVariableTwoIndex,
            statisticVariableTwoParameter: props.source.statisticVariableTwoParameter,
            operatorIndex: props.source.operator,
            submitting: false,
        }
    }

    componentWillReceiveProps(nextProps) {
        this.setState({
                name: nextProps.source.name,
                statisticVariableOneIndex: nextProps.source.statisticVariableOneIndex,
                statisticVariableOneParameter: nextProps.source.statisticVariableOneParameter,
                statisticVariableTwoIndex: nextProps.source.statisticVariableTwoIndex,
                statisticVariableTwoParameter: nextProps.source.statisticVariableTwoParameter,
                operatorIndex: nextProps.source.operator,
            }
        );
    }

    statisticIndex(statisticToFind) {
        return this.props.statisticVariables.findIndex(statistic => statistic.name === statisticToFind.name && statistic.education_program_type === statisticToFind.education_program_type);
    }

    operatorIndex(operatorToFind) {
        return this.props.operators.findIndex(operator => operator.type === operatorToFind.type);
    }

    submit() {
        if (!this.validate()) {
            return false;
        }

        const operator = this.props.operators[this.state.operatorIndex];
        const variableOne = this.props.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.props.statisticVariables[this.state.statisticVariableTwoIndex];

        this.setState({submitting: true});
        axios.put(`/api/statistics/${this.props.id}`, {
            name: this.state.name,
            operator: operator.type,
            statisticVariableOne: variableOne,
            statisticVariableOneParameter: this.state.statisticVariableOneParameter,
            statisticVariableTwo: variableTwo,
            statisticVariableTwoParameter: this.state.statisticVariableTwoParameter,
            educationProgramTypeId: variableOne.education_program_type
        }).then(response => {
            this.setState({submitting: false});
            this.props.onUpdated(response.data);
            this.props.history.push('/');
        }).catch(error => {
            console.log(error);
            this.setState({submitting: false});
        });


    }

    validate() {
        if (this.state.name === '') {
            alert(Lang.get('react.statistic.errors.name'));
            return false;
        }
        const operator = this.props.operators[this.state.operatorIndex];
        const variableOne = this.props.statisticVariables[this.state.statisticVariableOneIndex];
        const variableTwo = this.props.statisticVariables[this.state.statisticVariableTwoIndex];

        if (operator.type < 0 || operator.type > 3) { // 4 operators: +-*/
            return false;
        }
        if (variableOne.type === "collecteddatastatistic" && variableOne.hasParameters && this.state.statisticVariableOneParameter === '') {
            alert(Lang.get('react.statistic.errors.empty-variable-parameter'));
            return false;
        }
        if (variableTwo.type === "collecteddatastatistic" && variableTwo.hasParameters && this.state.statisticVariableTwoParameter === '') {
            alert(Lang.get('react.statistic.errors.empty-variable-parameter'));
            return false;
        }

        return true;
    }

    render() {
        if (this.props.loading) return null;

        return <div className="col-md-6">
            <Link to="/">
                <button type="button" className="btn">{Lang.get('tips.back')}</button>
            </Link>
            <h1>{Lang.get('statistics.edit')}</h1>
            <strong>{Lang.get('react.statistic.statistic-name')}</strong><br/>
            <input onChange={e => this.setState({name: e.target.value})} value={this.state.name}
                   className="form-control" type="text" maxLength={255}/>

            <div className="row">

                <div className="col-md-4">

                    <h4>{Lang.get('react.statistic.select-variable-one')}</h4>
                    <select className="form-control"
                            onChange={e => this.setState({statisticVariableOneIndex: parseInt(e.target.value)})}
                            value={this.state.statisticVariableOneIndex}>
                        {this.props.statisticVariables.map(
                            statisticVariable =>
                                <option key={`${statisticVariable.id}`}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name} -
                                    ({this.props.educationProgramTypes[statisticVariable.education_program_type].eptype_name})
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
                        {this.props.statisticVariables.filter(statisticVariable => {
                            if (this.state.statisticVariableOneIndex > 0) {
                                return this.props.statisticVariables[this.state.statisticVariableOneIndex].education_program_type === statisticVariable.education_program_type;
                            }
                            return true;
                        }).map(
                            statisticVariable =>
                                <option key={`${statisticVariable.id}`}
                                        value={this.statisticIndex(statisticVariable)}>
                                    {statisticVariable.name} -
                                    ({this.props.educationProgramTypes[statisticVariable.education_program_type].eptype_name})
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

            <br/>
            <button type="button" className="btn defaultButton" style={{maxWidth: '150px'}}
                    disabled={this.state.submitting}
                    onClick={() => this.submit()}>
                {this.state.submitting ?
                    '...' :
                    Lang.get('tips.save')
                }
            </button>

        </div>;
    }


}


const mapState = (state, {match, history}) => {
    const selectedStatistic = state.entities.statistics[match.params.id];

    if (selectedStatistic === undefined) {
        history.push('/');
        return {
            loading: true,
        }
    }

    const statisticVariables = Object.values(state.entities.statisticVariables).filter(statisticVariable => {
        // Check for c- (collectable) in id, those can be used for new statistics
        return String(statisticVariable.id).includes('c-');
    });

    const currentVariableOne = state.entities.statisticVariables[selectedStatistic.statistic_variable_one];
    const currentVariableTwo = state.entities.statisticVariables[selectedStatistic.statistic_variable_two];


    let indexVarOne = statisticVariables.findIndex(variable => variable.findable_id === (currentVariableOne.dataUnitMethod + '-' + selectedStatistic.education_program_type));

    const filteredStatisticVariables = statisticVariables.filter(statisticVariable => {
        if (indexVarOne >= 0) {
            return currentVariableOne.education_program_type === statisticVariable.education_program_type;
        }
        return true;
    });

    let indexVarTwo = filteredStatisticVariables.findIndex(variable => variable.findable_id === (currentVariableTwo.dataUnitMethod + '-' + selectedStatistic.education_program_type));

    if (indexVarOne === -1) indexVarOne = 0;
    if (indexVarTwo === -1) indexVarTwo = 0;


    return {
        history,
        id: selectedStatistic.id,
        statisticVariables,
        educationProgramTypes: state.entities.educationProgramTypes,
        operators: [
            {type: 0, label: "+"},
            {type: 1, label: "-"},
            {type: 2, label: "*"},
            {type: 3, label: "/"},
        ],
        source: {
            name: selectedStatistic.name || '',
            statisticVariableOneIndex: indexVarOne || 0,
            statisticVariableOneParameter: currentVariableOne.dataUnitParameterValue || '',
            statisticVariableTwoIndex: indexVarTwo || 0,
            statisticVariableTwoParameter: currentVariableTwo.dataUnitParameterValue || '',
            operator: selectedStatistic.operator
        }
    }
};

const mapDispatch = dispatch => {
    return {
        onUpdated: newEntity => {
            dispatch(actions.addEntities(normalize(newEntity, Schema.statistic).entities));
        }
    }
};

export default withRouter(connect(mapState, mapDispatch)(UpdateForm));