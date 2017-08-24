import AppDispatcher from './dispatcher';
import ActionTypes from './types/actionTypes';

class Actions{
    getSummaryTableData(){
        fetch('/ajax/get_summary_table_data', {
            credentials: 'include'
        })
            .then(response => response.json())
            .then(items => {
                AppDispatcher.dispatch({
                    eventName: ActionTypes.SET_SUMMARY_TABLE_DATA,
                    items
                });
            });
    }
}

export default new Actions();