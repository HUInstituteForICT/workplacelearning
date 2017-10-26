import React from "react";

export default class Row extends React.Component {

    constructor(props) {
        super(props);
        this.state = {

            visible: false

        }
    }

    render() {
        let activity = this.props.activity;
        let className = "fa fa-arrow-circle-o-" + (this.state.visible ? 'up' : 'down');
        return <tr className="activityExport">
            <td>
                {(activity.situation.length > 30 || activity.lessonsLearned.length > 30) &&
                <i onClick={() => this.setState({visible: !this.state.visible})}
                   className={className}/>
                }

            </td>
            <td>{activity.date}</td>
            <td>
                {activity.situation.length > 30 &&
                <span>
                        {this.state.visible && <span>{activity.situation}</span>}
                    {!this.state.visible && <span>{activity.situation.substr(0, 30)}...</span>}
                    </span>
                }
                {activity.situation.length <= 30 &&
                activity.situation
                }
            </td>
            <td>{activity.timeslot}</td>
            <td>{activity.resourcePerson}</td>
            <td>{activity.resourceMaterial}</td>
            <td>
                {activity.lessonsLearned.length > 30 &&
                <span>
                        {this.state.visible && <span>{activity.lessonsLearned}</span>}
                    {!this.state.visible && <span>{activity.lessonsLearned.substr(0, 30)}...</span>}
                    </span>
                }
                {activity.lessonsLearned.length <= 30 &&
                activity.lessonsLearned
                }
            </td>
            <td>{activity.learningGoal}</td>
            <td>{activity.competence}</td>
            <td>{activity.evidence !== "-" && <a href={activity.evidence}>download</a>}</td>
            <td><a href={activity.url}><i className="glyphicon glyphicon-pencil" aria-hidden="true"/></a></td>
        </tr>
    }

}