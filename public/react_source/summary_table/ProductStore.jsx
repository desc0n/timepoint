import AppDispatcher from './dispatcher';
import EventEmitter from 'events';
import ActionTypes from './types/actionTypes';

let _productsStates = {
    items: []
};

class ProductStore extends EventEmitter{
    emitChange(){
        this.emit(ActionTypes.SET_SUMMARY_TABLE_DATA);
    }
    addChangeListener(callback){
        this.on(ActionTypes.SET_SUMMARY_TABLE_DATA, callback);
    }
    setSummaryTableData(items){
        _productsStates.items = items;
    }
    getSummaryTableData(){
        return {
            items: _productsStates.items,
        };
    }
}

let ProductStoreInstance = new ProductStore();

ProductStoreInstance.dispatchToken = AppDispatcher.register(function(action){
        switch (action.eventName){
            case ActionTypes.SET_SUMMARY_TABLE_DATA:
                this.setSummaryTableData(action.items);
                break;
            default:
            // do nothing
        }
        this.emitChange();
    }.bind(ProductStoreInstance)
);

export default ProductStoreInstance;