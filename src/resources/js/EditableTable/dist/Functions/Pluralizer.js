"use strict";
var _a;
Object.defineProperty(exports, "__esModule", { value: true });
exports.Pluralize = void 0;
class Pluralize {
}
exports.Pluralize = Pluralize;
_a = Pluralize;
Pluralize.irregularPlurals = {
    child: 'children',
    person: 'people',
    man: 'men',
    woman: 'women',
    mouse: 'mice',
    goose: 'geese',
    tooth: 'teeth',
    foot: 'feet',
    ox: 'oxen',
    louse: 'lice',
    cactus: 'cacti',
    focus: 'foci',
    fungus: 'fungi',
    nucleus: 'nuclei',
    syllabus: 'syllabi',
    analysis: 'analyses',
    crisis: 'crises',
    diagnosis: 'diagnoses',
    thesis: 'theses',
    criterion: 'criteria',
    phenomenon: 'phenomena',
    index: 'indices',
    appendix: 'appendices',
    matrix: 'matrices',
    medium: 'media',
    datum: 'data',
    alumnus: 'alumni',
};
Pluralize.unchangingNouns = new Set([
    'hse',
    'kpi',
    'esg',
    'ghg',
    'hr',
    'scm',
    'eco',
    'it',
    'diginet_data',
    'compliance_data',
    'accounting',
    'dashboard',
    // Add more nouns that remain the same in singular and plural
]);
Pluralize.pluralize = (singular, count) => {
    if (count === 0)
        return '';
    if (count === 1)
        return singular;
    // Check if the noun is in the unchanging nouns set
    if (_a.unchangingNouns.has(singular)) {
        return singular;
    }
    // Check if the singular form is in the irregularPlurals map
    if (_a.irregularPlurals[singular]) {
        return _a.irregularPlurals[singular];
    }
    // Default rule for regular plurals
    return singular.endsWith('y') && !/[aeiou]y$/.test(singular)
        ? singular.slice(0, -1) + 'ies' // Words ending with "y" after a consonant
        : singular.endsWith('s') || singular.endsWith('sh') || singular.endsWith('ch')
            ? singular + 'es' // Words ending with "s", "sh", or "ch"
            : singular + 's'; // General case
};
//# sourceMappingURL=Pluralizer.js.map