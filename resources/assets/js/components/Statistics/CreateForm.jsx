import * as React from "react";
import axios from "axios";

export default class CreateForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            name: '',
            statisticVariableOneType: '',
            statisticVariableTwoType: '',
            statisticVariableOneFilters: [],
            statisticVariableTwoFilters: [],
            statisticVariableOneSelectType: 'count',
            statisticVariableTwoSelectType: 'count',
            operatorIndex: 0,
            submitting: false,
        };
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
        if(!this.validate()) {
            return false;
        }

        this.setState({submitting: true});
        axios.post('/api/statistics', {
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
            this.props.onCreated(response.data);
            this.setState({submitting: false});
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


        return <div>
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
            <p style={{whiteSpace: 'pre-line'}}>
                { Lang.get('statistics.variable-help') }
            </p>
            <button type="button" className="btn defaultButton"  disabled={this.state.submitting}
                    onClick={() => this.submit()}>
                {this.state.submitting ?
                    '...' :
                    Lang.get('react.statistic.create')
                }
            </button>

        </div>;
    }


}