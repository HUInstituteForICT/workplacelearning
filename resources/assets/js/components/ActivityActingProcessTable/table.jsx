import React from "react";
import Row from "./row";
import FilterRule from "./filterRule";
import _ from "lodash";
import ActingActivityProcessExporter from "../../services/ActingActivityProcessExporter";

export default class ActivityActingProcessTable extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            activities: window.activities,
            filters: this.buildFilter(window.activities),
            exports: ["csv", "txt"],
            selectedExport: "csv"
        };

        this.updateFilter = this.updateFilter.bind(this);
        this.exportHandler = this.exportHandler.bind(this);
    }

    // Build filter rules from the provided activity data
    buildFilter(activities) {
        let filters = {
            timeslot: {rules: [], selectedRules: []},
            learningGoal: {rules: [], selectedRules: []},
            competence: {rules: [], selectedRules: []}
        };


        // Build filters
        activities.map((activity) => {

            let timeslot = activity.timeslot;
            if (filters.timeslot.rules.indexOf(timeslot) === -1) {
                filters.timeslot.rules.push(timeslot);
            }

            let learningGoal = activity.learningGoal;
            if (filters.learningGoal.rules.indexOf(learningGoal) === -1) {
                filters.learningGoal.rules.push(learningGoal);
            }

            let competence = activity.competence;
            if (filters.competence.rules.indexOf(competence) === -1) {
                filters.competence.rules.push(competence);
            }


        });

        // Sorting string might get weird (10e lesuur becoming first item), so own callback for sort.
        filters.timeslot.rules.sort((a, b) => {
            return parseInt(a) - parseInt(b)
        });

        filters.learningGoal.rules.sort();
        filters.competence.rules.sort();

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
            // Filter for Timeslot
            .filter((activity) => {
                if (this.state.filters.timeslot.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.timeslot.selectedRules.indexOf(activity.timeslot) > -1;
            })
            // Filter for learningGoal
            .filter((activity) => {
                if (this.state.filters.learningGoal.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.learningGoal.selectedRules.indexOf(activity.learningGoal) > -1;
            })
            // Filter for competence
            .filter((activity) => {
                if (this.state.filters.competence.selectedRules.length === 0) {
                    return true;
                }

                return this.state.filters.competence.selectedRules.indexOf(activity.competence) > -1;
            });
    }


    exportHandler() {
        new ActingActivityProcessExporter(this.state.selectedExport, this.filterActivities(this.state.activities));
    }


    render() {
        let filteredActivities = this.filterActivities(this.state.activities);
        return <div>
            <h3 style={{cursor:"pointer"}} onClick={ () => {$('.filters').slideToggle()}}><i className="fa fa-arrow-circle-o-down" aria-hidden="true"/> Filters</h3>
            <div className="filters row" style={{display:"none"}}>
                <div className="timeslot col-md-4">
                    <h4>Tijdslot</h4>
                    <div className="buttons">
                        {this.state.filters.timeslot.rules.map(rule => {
                            return <FilterRule key={rule} type="timeslot" onClickHandler={this.updateFilter} rule={rule}
                                               activated={this.state.filters.timeslot.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

                <div className="learningGoal col-md-4">
                    <h4>Leervraag</h4>
                    <div className="buttons">
                        {this.state.filters.learningGoal.rules.map(rule => {
                            return <FilterRule key={rule} type="learningGoal" onClickHandler={this.updateFilter}
                                               rule={rule}
                                               activated={this.state.filters.learningGoal.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>
                <div className="competence col-md-4">
                    <h4>Competentie</h4>
                    <div className="buttons">
                        {this.state.filters.competence.rules.map(rule => {
                            return <FilterRule key={rule} type="competence" onClickHandler={this.updateFilter}
                                               rule={rule}
                                               activated={this.state.filters.competence.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

            </div>
            <br/>
            <div className="export" style={{paddingBottom:"15px"}}>

                <label>Export naar&nbsp;
                    <select onChange={e => {this.setState({selectedExport: e.target.value})}} defaultValue={this.state.selectedExport}>
                        {this.state.exports.map(type => {
                            return <option key={type} value={type}>{type}</option>
                        })}
                    </select>
                </label> &nbsp;
                <button className="btn btn-info" onClick={this.exportHandler} disabled={this.state.activities.length === 0}>exporteer</button>
            </div>

            <div className="table-responsive">
            <table className="table blockTable">
                <thead className="blue_tile">
                <tr>
                    <td></td>
                    <td>Datum</td>
                    <td>Situatie</td>
                    <td>Wanneer?</td>
                    <td>Met wie?</td>
                    <td>Theorie</td>
                    <td>Leerpunten en vervolg</td>
                    <td>Leervraag</td>
                    <td>Competentie</td>
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