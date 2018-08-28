import {combineReducers, createStore} from 'redux';
import {connect, Provider} from 'react-redux';
import React from "react";
import {HashRouter, Route, Switch, withRouter} from "react-router-dom";
import IndexPage from "./Tips/IndexPage";
import {actions as entityActions, reducer as entities} from "./Tips/redux/entities";
import {reducer as coupleStatistic} from "./Tips/redux/coupleStatistic";
import {actions as uiActions, reducer as tipEditPageUi} from "./Tips/redux/tipPageUi";
import axios from "axios";
import {normalize} from "normalizr";
import {Schema} from "../Schema";
import TipEditPage from "./Tips/TipEditPage";


const rootReducer = combineReducers({entities, coupleStatistic, tipEditPageUi});
const store = createStore(rootReducer);

window.getState = store.getState;

const mapping = {
    state: state => state,
    dispatch: dispatch => ({loadData: () => axios.get('/api/tips')
            .then(response => {
                dispatch(uiActions.addSelectableStatisticVariables(response.data.statisticVariables));
                delete response.data.statisticVariables;
                dispatch(entityActions.addEntities(normalize(response.data, Schema.loadSchema).entities));
            })})
};


class TipsApp extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            currentPage: 'tips'
        };
    }

    componentDidMount = () => {
        this.props.loadData();
    };

    render = () => <Switch>
        <Route exact path={'/tip/:id'} component={TipEditPage}/>
        <Route exact path={'/'} render={() => <IndexPage history={this.props.history} currentPage={this.state.currentPage} setCurrentPage={(page) => this.setState({currentPage: page})} />}/>
    </Switch>
}

const ConnectedTipsApp = withRouter(connect(mapping.state, mapping.dispatch)(TipsApp));


const root = () => <Provider store={store}>
    <HashRouter>
        <ErrorBoundary>
            <ConnectedTipsApp/>
        </ErrorBoundary>
    </HashRouter>
</Provider>;

export default root;


class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = {hasError: false, error: '', logged: false};
    }

    componentDidCatch(error, info) {
        // Display fallback UI
        this.setState({hasError: true});
        console.log(error, info);
        error.componentStack = info.componentStack;
        axios.post('/reactlog', {log: JSON.stringify(error, Object.getOwnPropertyNames(error))}).then(() => this.setState({logged: true}));

        // You can also log the error to an error reporting service
    }

    render() {
        if (this.state.hasError) {
            // You can render any custom fallback UI
            return <div>
                <h3>Whoops...</h3>
                <p>Something that should not have happened happened.</p>
                {this.state.logged && <p>
                    The error has been logged and will be fixed as soon as possible
                </p>}
            </div>;
        }
        return this.props.children;
    }
}