import React, {Component} from 'react';
import ProductStore from '../ProductStore';
import Actions from '../action';

class SummaryTable extends Component{
    constructor(props){
        super(props);
        this.state = {
            items: []
        };
        this._onChange = this._onChange.bind(this);
    }
    _onChange(){
        this.setState(ProductStore.getSummaryTableData());
    }
    componentDidMount(){
        ProductStore.addChangeListener(this._onChange);
        Actions.getSummaryTableData();
    }
    getMonth(){
        return (
            <tr>
            {this.state.items && Object.keys(this.state.items).map(function(year, yearItems) {
                return
                    {this.state.items[year] && Object.keys(this.state.items[year]).map(function(month, monthItems) {console.log(month);
                        return <td colSpan={Object.keys(this.state.items[year][month]).length}>{month}</td>
                    })}
            }.bind(this))}
            </tr>
        );
    }
    renderTbody(){
        return (
            <tbody>
                <tr>
                    {this.state.items && Object.keys(this.state.items).map(function(year, yearItems) {
                        return <td key={year} colSpan={Object.keys(this.state.items[year]).length}>{year}</td>
                    }.bind(this))}
                </tr>
                {this.getMonth()}
            </tbody>
        );
    }
    render(){
        return (
            <div className="row news-page">
                <div className="col-lg-12 form-group">
                    <h3>Результирующая таблица</h3>
                </div>
                <div className="col-lg-12 form-group">
                    <table className="table table-bordered">
                        {this.renderTbody()}
                    </table>
                </div>
            </div>
        );
    }
}

export default SummaryTable;