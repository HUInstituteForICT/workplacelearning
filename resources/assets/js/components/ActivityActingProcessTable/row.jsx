import React from "react";

export default class Row extends React.Component {

    constructor(props) {
        super(props);

    }

    render() {
        let activity = this.props.activity;
        return <tr>
            <td>{activity.date}</td>
            <td>{activity.situation}</td>
            <td>{activity.timeslot}</td>
            <td>{activity.resourcePerson}</td>
            <td>{activity.resourceMaterial}</td>
            <td>{activity.lessonsLearned}</td>
            <td>{activity.learningGoal}</td>
            <td>{activity.competence}</td>
            <td><a href={activity.url}><i className="glyphicon glyphicon-pencil" aria-hidden="true"></i></a></td>
        </tr>
    }

}