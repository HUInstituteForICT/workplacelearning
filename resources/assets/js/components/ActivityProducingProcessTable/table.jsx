import React from "react";
import Row from "./row";
import FilterRule from "./filterRule";
import _ from "lodash";
import ProducingActivityProcessExporter from "../../services/ProducingActivityProcessExporter";
import DatePicker from "react-datepicker";
import moment from "moment";
import 'react-datepicker/dist/react-datepicker.css';


export default class ActivityProducingProcessTable extends React.Component {

    constructor(props) {
        super(props);
        let earliestDate = moment(), latestDate = moment();
        window.activities.forEach(activity => {
            if(moment(activity.date, "DD-MM-YYYY") < earliestDate || earliestDate === undefined) {
                earliestDate = moment(activity.date, "DD-MM-YYYY");
            }
            if(moment(activity.date, "DD-MM-YYYY") > latestDate || latestDate === undefined) {
                latestDate = moment(activity.date, "DD-MM-YYYY");
            }
        });

        this.state = {
            activities: window.activities,
            filters: this.buildFilter(window.activities),
            exports: ["csv", "txt", "email", "word"],
            selectedExport: "txt",
            email: "",
            emailComment: "",

            emailAlert: null,
            startDate: earliestDate,
            endDate: latestDate,
        };

        this.updateFilter = this.updateFilter.bind(this);
        this.exportHandler = this.exportHandler.bind(this);
    }

    // Build filter rules from the provided activity data
    buildFilter(activities) {
        let filters = {
            duration: {rules: [], selectedRules: []},
            resourceDetail: {rules: [], selectedRules: []},
            category: {rules: [], selectedRules: []},
            difficulty: {rules: [], selectedRules: []}
        };


        // Build filters
        activities.map((activity) => {

            let duration = activity.duration;
            if (filters.duration.rules.indexOf(duration) === -1) {
                filters.duration.rules.push(duration);
            }

            let resourceDetail = activity.resourceDetail;
            if (filters.resourceDetail.rules.indexOf(resourceDetail) === -1) {
                filters.resourceDetail.rules.push(resourceDetail);
            }

            let category = activity.category;
            if (filters.category.rules.indexOf(category) === -1) {
                filters.category.rules.push(category);
            }

            let difficulty = activity.difficulty;
            if (filters.difficulty.rules.indexOf(difficulty) === -1) {
                filters.difficulty.rules.push(difficulty);
            }


        });

        filters.duration.rules.sort();
        filters.resourceDetail.rules.sort();
        filters.category.rules.sort();
        filters.difficulty.rules.sort();


        return filters;
    }

    updateFilter(type, rule) {
        // Deepclone the state
        let newFilterState = _.cloneDeep(this.state.filters);

        // Check if clicked rule is already "on"
        let index = newFilterState[type].selectedRules.indexOf(rule);
        if (index > -1) {
            // Disable rule
            newFilterState[type].selectedRules.splice(index, 1);
        } else {
            // Enable rule
            newFilterState[type].selectedRules.push(rule);
        }

        // Update state
        this.setState({filters: newFilterState});
    }

    filterActivities(activities) {

        return activities
            // Filter for Duration
            .filter((activity) => {
                if (this.state.filters.duration.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.duration.selectedRules.indexOf(activity.duration) > -1;
            })
            // Filter for resourceDetail
            .filter((activity) => {
                if (this.state.filters.resourceDetail.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.resourceDetail.selectedRules.indexOf(activity.resourceDetail) > -1;
            })
            // Filter for category
            .filter((activity) => {
                if (this.state.filters.category.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.category.selectedRules.indexOf(activity.category) > -1;
            })
            // Filter for difficulty
            .filter((activity) => {
                if (this.state.filters.difficulty.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.difficulty.selectedRules.indexOf(activity.difficulty) > -1;
            })
            .filter((activity) => {
                return moment(activity.date, "DD-MM-YYYY").isSameOrAfter(this.state.startDate) && moment(activity.date, "DD-MM-YYYY").isSameOrBefore(this.state.endDate)
            })
    }


    exportHandler() {
        const exporter = new ProducingActivityProcessExporter(this.state.selectedExport, this.filterActivities(this.state.activities));

        if(this.state.selectedExport === "email") {
            this.setState({emailAlert: undefined});
            exporter.mail(this.state.email, this.state.emailComment, response => {
                if(response.hasOwnProperty("data") && response.data.status === "success") {
                    this.setState({email: "", emailComment: '', emailAlert: true});
                } else {
                    this.setState({email: "", emailComment: '', emailAlert: true});
                }
                setTimeout(() => this.setState({emailAlert: null}), 3000);


            });
        } else if(this.state.selectedExport === "word") {
            exporter.txt();
            const exportText = exporter.outputData;
            axios.post('/activity-export-doc', {exportText})
                .then(response => {
                    console.log(response);
                    window.location.href = response.data.download;
                });
        } else {
            exporter[this.state.selectedExport]();
            exporter.download();
        }
    }


    render() {
        let filteredActivities = this.filterActivities(this.state.activities);
        return <div>
            <h3 style={{cursor:"pointer"}} onClick={ () => {$('.filters').slideToggle()}}><i className="fa fa-arrow-circle-o-down" aria-hidden="true"/> {Lang.get('react.filters')}</h3>
            <div className="filters row" style={{display:"none"}}>
                <div className="date col-md-2">
                    <h4>{ Lang.get('react.date') }</h4>
                    <div>
                        <strong>{Lang.get('react.startdate')}:</strong>
                        <DatePicker className={"form-control"} selected={this.state.startDate} dateFormat="DD/MM/YYYY" onChange={date => this.setState({startDate: date})} />
                    <br/>
                        <strong>{Lang.get('react.enddate')}:</strong>
                        <DatePicker className={"form-control"} selected={this.state.endDate} dateFormat="DD/MM/YYYY" onChange={date => this.setState({endDate: date})} />
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>
                <div className="duration col-md-2">
                    <h4>{Lang.get('react.time')}</h4>
                    <div className="buttons">
                        {this.state.filters.duration.rules.map(rule => {
                            return <FilterRule key={rule} type="duration" onClickHandler={this.updateFilter} rule={rule}
                                               activated={this.state.filters.duration.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

                <div className="resourceDetail col-md-2">
                    <h4>{Lang.get('react.aid')}</h4>
                    <div className="buttons">
                        {this.state.filters.resourceDetail.rules.map(rule => {
                            return <FilterRule key={rule} type="resourceDetail" onClickHandler={this.updateFilter}
                                               rule={rule}
                                               activated={this.state.filters.resourceDetail.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

                <div className="duration col-md-2">
                    <h4>{Lang.get('react.category')}</h4>
                    <div className="buttons">
                        {this.state.filters.category.rules.map(rule => {
                            return <FilterRule key={rule} type="category" onClickHandler={this.updateFilter} rule={rule}
                                               activated={this.state.filters.category.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

                <div className="duration col-md-2">
                    <h4>{Lang.get('react.complexity')}</h4>
                    <div className="buttons">
                        {this.state.filters.difficulty.rules.map(rule => {
                            return <FilterRule key={rule} type="difficulty" onClickHandler={this.updateFilter} rule={rule}
                                               activated={this.state.filters.difficulty.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

            </div>
            <br/>
            <div className="export" style={{paddingBottom:"15px"}}>

                <label>{Lang.get('react.export-to')}&nbsp;
                    <select onChange={e => {this.setState({selectedExport: e.target.value})}} defaultValue={this.state.selectedExport}>
                        {this.state.exports.map(type => {
                            return <option key={type} value={type}>{type}</option>
                        })}
                    </select>
                </label> &nbsp;
                <button className="btn btn-info" onClick={this.exportHandler} disabled={this.state.activities.length === 0 || (this.state.selectedExport === 'email' && (!this.state.email.includes('@') || !this.state.email.includes('.')) )}>{Lang.get('react.export')}</button>
                <br/>
                {this.state.selectedExport === 'email' &&
                    <div style={{maxWidth: "400px"}}>
                        <label>
                            {Lang.get('react.mail-to')}: <input type="email" className="form-control" onChange={e => this.setState({email: e.target.value})} value={this.state.email} />
                        </label><br/>
                        <label>
                            {Lang.get('react.mail-comment')}: <textarea className="form-control" onChange={e => this.setState({emailComment: e.target.value})} value={this.state.emailComment} />
                        </label>
                        {
                            this.state.emailAlert === undefined &&
                            <div className="alert alert-info" role="alert">{Lang.get('react.mail.sending')}</div>
                        }
                        {
                            this.state.emailAlert === true &&
                            <div className="alert alert-success" role="alert">{Lang.get('react.mail.sent')}</div>
                        }
                        {
                            this.state.emailAlert === false &&
                            <div className="alert alert-danger" role="alert">{Lang.get('react.mail.failed')}</div>
                        }
                    </div>
                }
            </div>

            <div className="table-responsive">
            <table className="table blockTable">
                <thead className="blue_tile">
                <tr>
                    <td></td>
                    <td>{Lang.get('react.date')}</td>
                    <td>{Lang.get('react.description')}</td>
                    <td>{Lang.get('react.time')}</td>
                    <td>{Lang.get('react.aid')}</td>
                    <td>{Lang.get('react.category')}</td>
                    <td>{Lang.get('react.complexity')}</td>
                    <td>{Lang.get('react.status')}</td>
                    <td>{/* Edit URL, no table header */}</td>
                </tr>
                </thead>
                <tbody>
                {filteredActivities.map((activity) => {
                    return <Row key={activity.id} activity={activity}/>
                })}

                </tbody>
            </table>
            </div>
        </div>
    }


}