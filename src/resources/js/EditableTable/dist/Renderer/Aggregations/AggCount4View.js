"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.AggCount4View = void 0;
const Functions_1 = require("../../Functions");
const Renderer4View_1 = require("../Renderer4View");
class AggCount4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    control() {
        const cellValue = this.cellValue;
        const column = this.column;
        const { unit = 'item', columnToLoad = 'name' } = column.rendererAttrs || {};
        const merged = (0, Functions_1.getForeignObjects)(cellValue);
        const { length } = merged;
        // console.log(cellValue, merged, length)
        const units = Functions_1.Str.pluralize(unit, length);
        const rendered = length ? `${length} ${units}` : ``;
        let titles = '';
        if (columnToLoad) {
            titles = merged
                .map((item) => (item && item[columnToLoad] ? item[columnToLoad] : ''))
                .join(', ');
        }
        this.tdTooltip = titles;
        return rendered;
    }
}
exports.AggCount4View = AggCount4View;
//# sourceMappingURL=AggCount4View.js.map