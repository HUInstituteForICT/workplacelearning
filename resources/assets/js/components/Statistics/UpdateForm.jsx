import * as React from "react";
import axios from "axios";
import {Link, withRouter} from "react-router-dom";
import {connect} from "react-redux";
import {actions} from "../Tips/redux/entities";
import {Schema} from "../../Schema";
import {normalize} from "normalizr";

class UpdateForm extends React.Component {

    constructor(props) {
        super(props);
        if (props.loading) {
            this.state = {};
            return;
        }
        this.state = {
            name: props.source.name,
            statisticVariableOneType: props.source.statisticVariableOneType,
            statisticVariableTwoType: props.source.statisticVariableTwoType,
            statisticVariableOneFilters: props.source.statisticVariableOneFilters,
            statisticVariableTwoFilters: props.source.statisticVariableTwoFilters,
            statisticVariableOneSelectType: props.source.statisticVariableOneSelectType,
            statisticVariableTwoSelectType: props.source.statisticVariableTwoSelectType,
            operatorIndex: props.source.operator,
            submitting: false,
        }
    }

    componentWillReceiveProps(nextProps) {
        this.setState(
            {
                name: nextProps.source.name,
                statisticVariableOneType: nextProps.source.statisticVariableOneType,
                statisticVariableTwoType: nextProps.source.statisticVariableTwoType,
                statisticVariableOneFilters: nextProps.source.statisticVariableOneFilters,
                statisticVariableTwoFilters: nextProps.source.statisticVariableTwoFilters,
                statisticVariableOneSelectType: nextProps.source.statisticVariableOneSelectType,
                statisticVariableTwoSelectType: nextProps.source.statisticVariableTwoSelectType,
                operatorIndex: nextProps.source.operator,
            }
        );
    }

    operatorIndex(operatorToFind) {
        return this.props.operators.findIndex(operator => operator.type === operatorToFind.type);
    }

    selectVariableType = (number, value) => {
        const variable = number === 'one' ? 'statisticVariableOne' : 'statisticVariableTwo';
        this.setState({
            [variable + 'Type']: value,
            [variable + 'Filters']: JSON.parse(JSON.stringify(this.props.variableFilters[value])),
        })
    };

    updateFilter = (number, filterIndex, parameterIndex, value) => {

        const filter = number === 'one' ? {...this.state.statisticVariableOneFilters[filterIndex]} : {...this.state.statisticVariableTwoFilters[filterIndex]};
        const parameters = [...filter.parameters];
        const parameter = parameters[parameterIndex];
        parameter.value = value;

        parameters[parameterIndex] = parameter;
        filter.parameters = parameters;

        const filters = number === 'one' ? [...this.state.statisticVariableOneFilters] : [...this.state.statisticVariableTwoFilters];
        filters[filterIndex] = filter;
        number === 'one' ? this.setState({statisticVariableOneFilters: filters}) : this.setState({statisticVariableTwoFilters: filters});
    };


    submit() {
        if (!this.validate()) {
            return false;
        }

        this.setState({submitting: true});
        axios.put(`/api/statistics/${this.props.id}`, {
            name: this.state.name,
            operator: this.props.operators[this.state.operatorIndex].type,
            statisticVariableOne: {
                type: this.state.statisticVariableOneType,
                filters: this.state.statisticVariableOneFilters,
                selectType: this.state.statisticVariableOneSelectType
            },
            statisticVariableTwo: {
                type: this.state.statisticVariableTwoType,
                filters: this.state.statisticVariableTwoFilters,
                selectType: this.state.statisticVariableTwoSelectType
            },
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
        if(this.state.name === '') {
            alert(Lang.get('react.statistic.errors.name'));
            return false;
        }
        const operator = this.props.operators[this.state.operatorIndex];

        return !(operator.type < 0 || operator.type > 3);
    }

    render() {
        if (this.props.loading) return null;


        return <div className="col-md-6">
            <Link to="/">
                <button type="button" className="btn">{Lang.get('tips.back')}</button>
            </Link>
            <h1>{Lang.get('statistics.edit')}</h1>
            <div>
                <strong>{Lang.get('react.statistic.statistic-name')}</strong><br/>
                <input onChange={e => this.setState({name: e.target.value})} value={this.state.name}
                       className="form-control" type="text" maxLength={255}/>

                <div className="row">

                    <div className="col-md-4">

                        <h4>{Lang.get('react.statistic.select-variable-one')}</h4>
                        <select className="form-control"
                                onChange={e => this.selectVariableType('one', e.target.value)}
                                value={this.state.statisticVariableOneType}>
                            <option value='' disabled/>
                            <option value="acting">Acting</option>
                            <option value="producing">Producing</option>
                        </select>


                        {
                            this.state.statisticVariableOneFilters.map((filter, filterIndex) => {

                                return <div key={filter.name}>
                                    <strong>{filter.name}</strong>
                                    {
                                        filter.parameters.map((parameter, parameterIndex) => {
                                            return <div key={parameter.name}>
                                                <input value={parameter.value || ''}
                                                       placeholder={parameter.name}
                                                       onChange={e => this.updateFilter('one', filterIndex, parameterIndex, e.target.value)}
                                                       type="text" className="form-control" maxLength={255}/>
                                            </div>;
                                        })
                                    }
                                </div>;


                            })
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
                        <select className="form-control"
                                onChange={e => this.selectVariableType('two', e.target.value)}
                                value={this.state.statisticVariableTwoType}>
                            <option value='' disabled/>
                            <option value="acting">Acting</option>
                            <option value="producing">Producing</option>
                        </select>


                        {
                            this.state.statisticVariableTwoFilters.map((filter, filterIndex) => {

                                return <div key={filter.name}>
                                    <strong>{filter.name}</strong>
                                    {
                                        filter.parameters.map((parameter, parameterIndex) => {
                                            return <div key={parameter.name}>
                                                <input value={parameter.value || ''}
                                                       placeholder={parameter.name}
                                                       onChange={e => this.updateFilter('two', filterIndex, parameterIndex, e.target.value)}
                                                       type="text" className="form-control" maxLength={255}/>
                                            </div>;
                                        })
                                    }
                                </div>;


                            })
                        }
                    </div>
                </div>

                <br/>
                <button type="button" className="btn defaultButton" disabled={this.state.submitting}
                        onClick={() => this.submit()}>
                    {this.state.submitting ?
                        '...' :
                        Lang.get('react.statistic.save')
                    }
                </button>

            </div>
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

    const currentVariableOne = state.entities.statisticVariables[selectedStatistic.statistic_variable_one];
    const currentVariableTwo = state.entities.statisticVariables[selectedStatistic.statistic_variable_two];

    return {
        history,
        id: selectedStatistic.id,
        operators: [
            {type: 0, label: "+"},
            {type: 1, label: "-"},
            {type: 2, label: "*"},
            {type: 3, label: "/"},
        ],
        variableFilters: state.tipEditPageUi.variableFilters,
        source: {
            name: selectedStatistic.name || '',
            statisticVariableOneType: currentVariableOne.type,
            statisticVariableOneFilters: currentVariableOne.filters,
            statisticVariableOneSelectType: currentVariableOne.selectType,
            statisticVariableTwoType: currentVariableTwo.type,
            statisticVariableTwoFilters: currentVariableTwo.filters,
            statisticVariableTwoSelectType: currentVariableTwo.selectType,
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