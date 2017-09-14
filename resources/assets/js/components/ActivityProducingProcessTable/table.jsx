import React from "react";
import Row from "./row";
import FilterRule from "./filterRule";
import _ from "lodash";
import ProducingActivityProcessExporter from "../../services/ProducingActivityProcessExporter";

export default class ActivityProducingProcessTable extends React.Component {

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
            duration: {rules: [], selectedRules: []},
            resourceDetail: {rules: [], selectedRules: []},
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


        });

        filters.duration.rules.sort();
        filters.resourceDetail.rules.sort();

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
    }


    exportHandler() {
        new ProducingActivityProcessExporter(this.state.selectedExport, this.filterActivities(this.state.activities));
    }


    render() {
        let filteredActivities = this.filterActivities(this.state.activities);
        return <div>
            <h3 style={{cursor:"pointer"}} onClick={ () => {$('.filters').slideToggle()}}><i className="fa fa-arrow-circle-o-down" aria-hidden="true"/> Filters</h3>
            <div className="filters row" style={{display:"none"}}>
                <div className="duration col-md-4">
                    <h4>Tijd</h4>
                    <div className="buttons">
                        {this.state.filters.duration.rules.map(rule => {
                            return <FilterRule key={rule} type="duration" onClickHandler={this.updateFilter} rule={rule}
                                               activated={this.state.filters.duration.selectedRules.indexOf(rule) > -1}/>
                        })}
                    </div>
                    <div style={{clear: 'both'}}/>
                </div>

                <div className="resourceDetail col-md-4">
                    <h4>Hulpbron</h4>
                    <div className="buttons">
                        {this.state.filters.resourceDetail.rules.map(rule => {
                            return <FilterRule key={rule} type="resourceDetail" onClickHandler={this.updateFilter}
                                               rule={rule}
                                               activated={this.state.filters.resourceDetail.selectedRules.indexOf(rule) > -1}/>
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
                    <td>Omschrijving</td>
                    <td>Tijd</td>
                    <td>Hulpbron</td>
                    <td>Categorie</td>
                    <td>Complexiteit</td>
                    <td>Status</td>
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