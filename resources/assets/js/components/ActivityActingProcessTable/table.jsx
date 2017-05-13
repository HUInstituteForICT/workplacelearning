import React from "react";
import Row from "./row";
import FilterForm from "./filterForm"

export default class ActivityActingProcessTable extends React.Component {

    constructor(props) {
        super(props);
        this.state = {activities: window.activities}
    }

    setFilter(filter) {
        this.setState({filter:filter});
    }

    filterActivities(activities) {

        return activities;
    }

    render() {
        let filteredActivities = this.filterActivities(this.state.activities);
        return <div>

            <FilterForm setFilter={this.setFilter} activities={this.state.activities}/>

            <table className="table blockTable col-md-12">
                <thead className="blue_tile">
                <tr>
                    <td>Datum</td>
                    <td>Situatie</td>
                    <td>Wanneer?</td>
                    <td>Met wie?</td>
                    <td>Theorie</td>
                    <td>Leerpunten en vervolg</td>
                    <td>Leervraag</td>
                    <td>Competentie</td>
                    <td>{/* Edit URL */}</td>
                </tr>
                </thead>
                <tbody>
                {filteredActivities.map((activity) => {
                    return <Row key={activity.id} activity={activity} />
                })}
                </tbody>
            </table>
        </div>
    }


}