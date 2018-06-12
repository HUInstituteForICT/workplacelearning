import * as React from "react";
import axios from "axios";
import {connect} from "react-redux";

class CreateForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            name: '',
            education_program_type: 'acting',
            select_type: 'count',
            statisticVariableOneFilters: JSON.parse(JSON.stringify(this.props.variableFilters['acting'])),
            statisticVariableTwoFilters: JSON.parse(JSON.stringify(this.props.variableFilters['acting'])),
            operatorIndex: 3,
            submitting: false,
        };
    }


    operatorIndex(operatorToFind) {
        return this.props.operators.findIndex(operator => operator.type === operatorToFind.type);
    }

    selectEducationProgramType = (value) => {
        this.setState({
            education_program_type: value,
            statisticVariableOneFilters: JSON.parse(JSON.stringify(this.props.variableFilters[value])),
            statisticVariableTwoFilters: JSON.parse(JSON.stringify(this.props.variableFilters[value])),
        });
        // If switched to acting, check if selecttype is on hours, if so, change to count as Acting doesnt support hours selecttype
        if (value === 'acting' && this.state.select_type === 'hours') {
            this.setState({select_type: 'count'})
        }
    };

    selectSelectType = (value) => {
        this.setState({select_type: value});
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
        axios.post('/api/statistics', {
            name: this.state.name,
            operator: this.props.operators[this.state.operatorIndex].type,
            education_program_type: this.state.education_program_type,
            select_type: this.state.select_type,
            statisticVariableOne: {
                filters: this.state.statisticVariableOneFilters,
            },
            statisticVariableTwo: {
                filters: this.state.statisticVariableTwoFilters,
            },
        }).then(response => {
            this.setState({submitting: false});
            this.props.onCreated(response.data);
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

        if (operator.type < 0 || operator.type > 3) return false;
        if (this.state.education_program_type === '') {
            alert(Lang.get('react.statistic.errors.select-two-variables'));
            return false;
        }

        return true;
    }

    render() {
        return <div>
            <div className="row">
                <div className="col-lg-12" id="step-4">
                    <strong>{Lang.get('react.statistic.statistic-name')}</strong><br/>
                    <input onChange={e => this.setState({name: e.target.value})} value={this.state.name}
                           className="form-control" type="text" maxLength={255}/>
                </div>
            </div>
            <br/>
            <div className="row">
                <div className="col-lg-6" id="step-5">
                    <strong>{Lang.get('statistics.activity-type')}</strong>
                    <select className="form-control"
                            onChange={e => this.selectEducationProgramType(e.target.value)}
                            value={this.state.education_program_type}>
                        <option value="acting">Acting</option>
                        <option value="producing">Producing</option>
                    </select>
                </div>
                <div className="col-lg-6" id="step-6">
                    <strong>{Lang.get('statistics.select')}</strong>
                    <select className="form-control"
                            onChange={e => this.selectSelectType(e.target.value)}
                            value={this.state.select_type}>
                        <option value="count">{Lang.get('statistics.variable-select-count')}</option>
                        <option value="hours"
                                disabled={this.state.education_program_type === 'acting'}>{Lang.get('statistics.variable-select-hours')}</option>
                    </select>
                </div>
            </div>
            <hr/>
            <div className="row">
                <div className="col-md-4" id="step-7-and-8">

                    <h4>{Lang.get('react.statistic.select-variable-one')} filters</h4>


                        {
                            this.state.statisticVariableOneFilters.map((filter, filterIndex) => {

                                return <div key={filter.name}>
                                    <strong>{Lang.get('statistics.filters.' + filter.name)}</strong>
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

                <div className="col-md-4" id="step-9">
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


                <div className="col-md-4" id="step-10">

                    <h4>{Lang.get('react.statistic.select-variable-two')} filters</h4>
                    {
                        this.state.statisticVariableTwoFilters.map((filter, filterIndex) => {

                            return <div key={filter.name}>
                                <strong>{Lang.get('statistics.filters.' + filter.name)}</strong>
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
                {Lang.get('statistics.variable-help')}
            </p>
            <button id="step-11" type="button" className="btn btn-primary" disabled={this.state.submitting}
                    onClick={() => this.submit()}>

                {this.state.submitting &&
                <span className="glyphicon glyphicon-refresh glyphicon-refresh-animate"/>}
                {!this.state.submitting && <span>{Lang.get('react.statistic.create')}</span>}
            </button>

        </div>;
    }
}

const mapping = {
    state: state => ({
        variableFilters: state.tipEditPageUi.variableFilters,
        operators: [
            {type: 0, label: "+"},
            {type: 1, label: "-"},
            {type: 2, label: "*"},
            {type: 3, label: "/"},
        ],
    }),
};

export default connect(mapping.state, mapping.dispatch)(CreateForm);