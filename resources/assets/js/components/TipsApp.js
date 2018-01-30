import {combineReducers, createStore} from 'redux';
import {connect, Provider} from 'react-redux';
import React from "react";
import {HashRouter, Route, Switch, withRouter} from "react-router-dom";
import IndexPage from "./Tips/IndexPage";
import {actions, reducer as entities} from "./Tips/redux/entities";
import {reducer as coupleStatistic} from "./Tips/redux/coupleStatistic";
import axios from "axios";
import {normalize} from "normalizr";
import {loadSchema} from "../Schema";
import TipEditPage from "./Tips/TipEditPage";

const rootReducer = combineReducers({entities, coupleStatistic});
const store = createStore(rootReducer);

window.getState = store.getState;

const mapping = {
    state: state => state,
    dispatch: dispatch => ({loadData: () => axios.get('/api/tips').then(response => dispatch(actions.addEntities(normalize(response.data, loadSchema).entities)))})
};


class TipsApp extends React.Component {

    componentDidMount = () => {
        this.props.loadData();
    };

    render = () => <Switch>
        <Route exact path={'/tip/:id'} component={TipEditPage}/>
        <Route exact path={'/'} component={IndexPage}/>
    </Switch>
}

const ConnectedTipsApp = withRouter(connect(mapping.state, mapping.dispatch)(TipsApp));


const root = () => <Provider store={store}>
    <HashRouter>
        <ConnectedTipsApp/>
    </HashRouter>
</Provider>;

export default root;