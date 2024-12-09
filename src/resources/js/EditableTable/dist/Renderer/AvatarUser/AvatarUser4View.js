"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.AvatarUser4View = void 0;
const Functions_1 = require("../../Functions");
const Renderer4View_1 = require("../Renderer4View");
class AvatarUser4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    control() {
        const cellValue = this.cellValue;
        const column = this.column;
        const { maxToShow = 4 } = column.rendererAttrs || {};
        const merged = (0, Functions_1.getForeignObjects)(cellValue);
        const divs = merged
            .slice(0, maxToShow)
            .map((item) => {
            if (!item)
                return '';
            const classList = `h-10 w-10 rounded-full p-1`;
            const img = `<img src="${item.src}" class="${classList}" alt="${item.name}" />`;
            const tooltip = Functions_1.Str.makeId(item.id);
            return `<div class="flex items-center border rounded" title="${tooltip}">
                    ${img} 
                    <span class="font-semibold">
                        ${item.name}
                    </span>
                </div>`;
        })
            .join('');
        const more = merged.length - maxToShow;
        const moreTitle = merged
            .slice(maxToShow)
            .map((item) => `${Functions_1.Str.makeId(item.id)} - ${item.name}`)
            .join('\n');
        const moreClass = `p-1 rounded-full border cursor-pointer`;
        const moreSpan = more > 0 ? `<span class="${moreClass}" title="${moreTitle}">+${more}</span>` : ``;
        const gridClass = `grid grid-cols-${merged.length > 1 ? 2 : 1}`;
        const widthClass = `${merged.length > 1 ? 'w-11/12' : 'w-full'}`;
        const rendered = `<div class="flex items-center gap-1">
                <div class="${gridClass} ${widthClass}">
                    ${divs}
                </div>
                <div>
                    ${moreSpan}
                </div>
            </div>`;
        return rendered;
    }
}
exports.AvatarUser4View = AvatarUser4View;
//# sourceMappingURL=AvatarUser4View.js.map