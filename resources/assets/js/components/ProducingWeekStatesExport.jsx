import * as React from "react";
import DatePicker from "react-datepicker";
import moment from "moment";

export default class ProducingWeekStatesExport extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            startDate: moment(props.earliest, "YYYY/MM/DD").toDate(),
            endDate: moment(props.latest, "YYYY/MM/DD").toDate(),
        };
    }


    render() {
        return <div className={'row'}>
            <div className={"col-md-2"}>
                <strong>{ Lang.get('react.startdate')}</strong><br/>
                <DatePicker className={"form-control"} selected={this.state.startDate} dateFormat="dd/MM/yyyy"
                            onChange={date => this.setState({startDate: date})}/>
                <br/><br/>
                <strong>{ Lang.get('react.enddate')}</strong><br/>
                <DatePicker className="form-control" selected={this.state.endDate} endDate={this.state.endDate} dateFormat="dd/MM/yyyy"
                            onChange={date => this.setState({endDate: date})}/>
            </div>
            <div className={"col-md-1"}>
                <a href={this.props.url + "?startDate=" + moment(this.state.startDate).unix() + "&endDate=" + moment(this.state.endDate).unix()} target={"_blank"}  className="btn btn-info" role="button">{Lang.get('react.export')}</a>
            </div>
        </div>;
    }


}