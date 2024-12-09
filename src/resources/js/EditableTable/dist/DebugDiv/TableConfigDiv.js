"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TableConfigDiv = void 0;
const TableConfigDiv = (params) => {
    return `<ul>
        ${Object.entries(params.tableConfig)
        .filter(([_, value]) => value)
        .filter(([key, _]) => key !== 'classList')
        .map(([key, value]) => {
        if (typeof value === 'object') {
            const items = Object.entries(value)
                .filter(([_, value]) => value)
                .map(([key, value]) => {
                return `<li class="ml-20">${key}: ${JSON.stringify(value)}</li>`;
            });
            return `<li class="ml-10">${key}:</li><ul>${items.join('')}</ul>`;
        }
        else
            return `<li class="ml-10">${key}: ${value}</li>`;
    })
        .join('')}
    </ul>`;
};
exports.TableConfigDiv = TableConfigDiv;
//# sourceMappingURL=TableConfigDiv.js.map