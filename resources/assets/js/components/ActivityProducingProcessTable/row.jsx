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
                {(activity.description.length > 30) &&
                <i onClick={() => this.setState({visible: !this.state.visible})}
                   className={className}/>
                }

            </td>
            <td>{activity.date}</td>
            <td>
                {activity.description.length > 30 &&
                <span>
                        {this.state.visible && <span>{activity.description}</span>}
                    {!this.state.visible && <span>{activity.description.substr(0, 30)}...</span>}
                    </span>
                }
                {activity.description.length <= 30 &&
                activity.description
                }
            </td>
            <td>{activity.duration}</td>
            <td>{activity.resourceDetail}</td>
            <td>{activity.category}</td>
            <td>
                {activity.difficulty}
            </td>
            <td>{activity.status}</td>
            <td><a href={activity.url}><i className="glyphicon glyphicon-pencil" aria-hidden="true"/></a></td>
        </tr>
    }

}