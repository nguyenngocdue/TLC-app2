"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getNotFound = exports.getDataSource = exports.getDataSourceFromK = void 0;
const getDataSourceFromK = (dataSourceKey, valueField = 'id') => {
    const finalKey = `${dataSourceKey}_by_${valueField}`;
    if (k_by[finalKey] === undefined) {
        k_by[finalKey] = {};
        if (k[dataSourceKey]) {
            for (let i = 0; i < k[dataSourceKey].length; i++) {
                const item = k[dataSourceKey][i];
                const valueOfKey = item[valueField];
                // console.log(dataSourceKey, valueOfKey, item)
                k_by[finalKey][valueOfKey] = item;
            }
        }
        else {
            console.error('dataSourceKey not found', dataSourceKey);
        }
        // } else {
        // console.log('cache hit', finalKey)
    }
    return k_by[finalKey];
};
exports.getDataSourceFromK = getDataSourceFromK;
const getDataSourceFromKBy = (column) => {
    const { rendererAttrs = {} } = column;
    const { dataSourceKey, valueField = 'id' } = rendererAttrs;
    if (!dataSourceKey) {
        console.error('dataSourceKey is not defined', column);
        return {};
    }
    return (0, exports.getDataSourceFromK)(dataSourceKey, valueField);
};
const getDataSource = (column) => {
    let cbbDataSource = {};
    const { rendererAttrs = {} } = column;
    const { dataSourceKey, dataSource = {} } = rendererAttrs;
    if (typeof dataSourceKey == 'string') {
        cbbDataSource = getDataSourceFromKBy(column);
    }
    else {
        cbbDataSource = dataSource;
    }
    return cbbDataSource;
};
exports.getDataSource = getDataSource;
const getNotFound = () => {
    return `<div component="CacheKByKey_getNotFound"></div>`;
    // return `<div class="text-orange-300 text-center font-bold bg-orange-700 rounded">NOT FOUND ${valueField} ${cellValue}</div>`
};
exports.getNotFound = getNotFound;
//# sourceMappingURL=CacheKByKey.js.map