!(function (t, n) {
    'object' == typeof exports && 'object' == typeof module
        ? (module.exports = n())
        : 'function' == typeof define && define.amd
        ? define([], n)
        : 'object' == typeof exports
        ? (exports.VNnum2words = n())
        : (t.VNnum2words = n())
})(this, function () {
    return (function (t) {
        var n = {}
        function e(r) {
            if (n[r]) return n[r].exports
            var o = (n[r] = { i: r, l: !1, exports: {} })
            return t[r].call(o.exports, o, o.exports, e), (o.l = !0), o.exports
        }
        return (
            (e.m = t),
            (e.c = n),
            (e.d = function (t, n, r) {
                e.o(t, n) || Object.defineProperty(t, n, { enumerable: !0, get: r })
            }),
            (e.r = function (t) {
                'undefined' != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, { value: 'Module' }),
                    Object.defineProperty(t, '__esModule', { value: !0 })
            }),
            (e.t = function (t, n) {
                if ((1 & n && (t = e(t)), 8 & n)) return t
                if (4 & n && 'object' == typeof t && t && t.__esModule) return t
                var r = Object.create(null)
                if ((e.r(r), Object.defineProperty(r, 'default', { enumerable: !0, value: t }), 2 & n && 'string' != typeof t))
                    for (var o in t)
                        e.d(
                            r,
                            o,
                            function (n) {
                                return t[n]
                            }.bind(null, o),
                        )
                return r
            }),
            (e.n = function (t) {
                var n =
                    t && t.__esModule
                        ? function () {
                              return t.default
                          }
                        : function () {
                              return t
                          }
                return e.d(n, 'a', n), n
            }),
            (e.o = function (t, n) {
                return Object.prototype.hasOwnProperty.call(t, n)
            }),
            (e.p = ''),
            e((e.s = 4))
        )
    })([
        function (t, n, e) {
            var r = e(10).Symbol
            t.exports = r
        },
        function (t, n, e) {
            var r = e(0),
                o = e(15),
                u = e(16),
                i = r ? r.toStringTag : void 0
            t.exports = function (t) {
                return null == t ? (void 0 === t ? '[object Undefined]' : '[object Null]') : i && i in Object(t) ? o(t) : u(t)
            }
        },
        function (t, n) {
            t.exports = function (t) {
                return null != t && 'object' == typeof t
            }
        },
        function (t, n) {
            t.exports = function (t) {
                var n = typeof t
                return null != t && ('object' == n || 'function' == n)
            }
        },
        function (t, n, e) {
            const r = e(5),
                o = e(6),
                u = e(18),
                i = { 0: 'không', 1: 'một', 2: 'hai', 3: 'ba', 4: 'bốn', 5: 'năm', 6: 'sáu', 7: 'bảy', 8: 'tám', 9: 'chín' },
                c = { 1: 'mốt', 4: 'tư', 5: 'lăm' },
                f = (t) => {
                    let n = t[0],
                        e = t[1]
                    return 1 == n
                        ? 0 == e
                            ? 'mười'
                            : 'muời ' + i[e]
                        : 0 == e && 1 != n
                        ? i[n] + ' mươi'
                        : 1 == e && 1 != n
                        ? i[n] + ' mươi ' + c[1]
                        : 5 == e
                        ? 1 == n
                            ? c[n] + ' ' + i[5]
                            : i[n] + ' mươi ' + c[5]
                        : 4 == e
                        ? 1 == n
                            ? c[n] + ' ' + i[4]
                            : i[n] + ' mươi ' + c[4]
                        : i[n] + ' mươi ' + i[e]
                },
                l = (t) =>
                    1 === t.length
                        ? i[t[0]]
                        : 2 === t.length
                        ? f(t)
                        : ((t) => {
                              let n = t[0],
                                  e = t[1],
                                  r = t[2]
                              return 0 == n && 0 == e && 0 == r
                                  ? ''
                                  : 0 == e && 0 == r
                                  ? i[n] + ' trăm'
                                  : 0 != r && 0 == e
                                  ? i[n] + ' trăm linh ' + i[r]
                                  : i[n] + ' trăm ' + f([e, r])
                          })(t),
                a = (t) => ('000000' === r(o(t), '') ? '' : '' === l(t[0]) ? 'không nghìn ' + l(t[1]) : l(t[0]) + ' nghìn ' + l(t[1])),
                s = (t) => {
                    if ('000000000' === r(o(t), '')) return ''
                    if ('' === l(t[0])) return t.splice(0, 1), 'không triệu ' + a(t)
                    {
                        let n = ''
                        return (n += l(t[0]) + ' triệu '), t.splice(0, 1), (n += a(t)), n
                    }
                },
                p = (t, n) => {
                    const e = t.length % n
                    return e ? [t.slice(0, e), ...u(t.slice(e), n)] : u(t, n)
                }
            t.exports = function (t) {
                var n = ''
                let e = (t = BigInt(t)).toString().split(''),
                    r = p(e, 3)
                if ((1 === r.length ? (n += l(r[0])) : 2 === r.length ? (n += a(r)) : 3 === r.length && (n += s(r)), r.length >= 4)) {
                    var u = p(r, 3)
                    let t = [...u]
                    if (
                        (t.shift(),
                        (t = o(t)),
                        (t = t.find(function (t) {
                            return 0 != t
                        })),
                        null == t)
                    ) {
                        1 === u[0].length ? (n += l(r[0])) : 2 === u[0].length ? (n += a(r)) : 3 === u[0].length && (n += s(r))
                        for (let t = 1; t <= u.length - 1; t++) n += ' tỷ'
                    } else {
                        let t = u.length
                        for (let e = 0; e <= u.length - 1; e++) {
                            let r = ''
                            ;(t -= 1),
                                1 === u[e].length
                                    ? (r += l(u[e][0]))
                                    : 2 === u[e].length
                                    ? (r += a(u[e]))
                                    : 3 === u[e].length && (r += s(u[e])),
                                (n += '' == r ? 'không' : r)
                            for (let e = 1; e <= t; e++) n += ' tỷ '
                            n = (n = n.replace(/(\s)+/gi, ' ')).replace(/không (tỷ )+/gi, ' ')
                        }
                    }
                }
                return (n = (n = (n = (n = (n = n.replace(/(\s)+/gi, ' ')).replace(
                    /không (((?<=[\s,.:;"']|^)tỷ(?=[\s,.:;"']|$)(\s+\1)+)+)(không triệu) (không nghìn)/gi,
                    ' ',
                )).replace(/(\s)+/gi, ' ')).replace(/không triệu không nghìn/gi, ' ')).replace(/(\s)+/gi, ' '))
            }
        },
        function (t, n) {
            var e = Array.prototype.join
            t.exports = function (t, n) {
                return null == t ? '' : e.call(t, n)
            }
        },
        function (t, n, e) {
            var r = e(7)
            t.exports = function (t) {
                return (null == t ? 0 : t.length) ? r(t, 1 / 0) : []
            }
        },
        function (t, n, e) {
            var r = e(8),
                o = e(9)
            t.exports = function t(n, e, u, i, c) {
                var f = -1,
                    l = n.length
                for (u || (u = o), c || (c = []); ++f < l; ) {
                    var a = n[f]
                    e > 0 && u(a) ? (e > 1 ? t(a, e - 1, u, i, c) : r(c, a)) : i || (c[c.length] = a)
                }
                return c
            }
        },
        function (t, n) {
            t.exports = function (t, n) {
                for (var e = -1, r = n.length, o = t.length; ++e < r; ) t[o + e] = n[e]
                return t
            }
        },
        function (t, n, e) {
            var r = e(0),
                o = e(13),
                u = e(17),
                i = r ? r.isConcatSpreadable : void 0
            t.exports = function (t) {
                return u(t) || o(t) || !!(i && t && t[i])
            }
        },
        function (t, n, e) {
            var r = e(11),
                o = 'object' == typeof self && self && self.Object === Object && self,
                u = r || o || Function('return this')()
            t.exports = u
        },
        function (t, n, e) {
            ;(function (n) {
                var e = 'object' == typeof n && n && n.Object === Object && n
                t.exports = e
            }).call(this, e(12))
        },
        function (t, n) {
            var e
            e = (function () {
                return this
            })()
            try {
                e = e || new Function('return this')()
            } catch (t) {
                'object' == typeof window && (e = window)
            }
            t.exports = e
        },
        function (t, n, e) {
            var r = e(14),
                o = e(2),
                u = Object.prototype,
                i = u.hasOwnProperty,
                c = u.propertyIsEnumerable,
                f = r(
                    (function () {
                        return arguments
                    })(),
                )
                    ? r
                    : function (t) {
                          return o(t) && i.call(t, 'callee') && !c.call(t, 'callee')
                      }
            t.exports = f
        },
        function (t, n, e) {
            var r = e(1),
                o = e(2)
            t.exports = function (t) {
                return o(t) && '[object Arguments]' == r(t)
            }
        },
        function (t, n, e) {
            var r = e(0),
                o = Object.prototype,
                u = o.hasOwnProperty,
                i = o.toString,
                c = r ? r.toStringTag : void 0
            t.exports = function (t) {
                var n = u.call(t, c),
                    e = t[c]
                try {
                    t[c] = void 0
                    var r = !0
                } catch (t) {}
                var o = i.call(t)
                return r && (n ? (t[c] = e) : delete t[c]), o
            }
        },
        function (t, n) {
            var e = Object.prototype.toString
            t.exports = function (t) {
                return e.call(t)
            }
        },
        function (t, n) {
            var e = Array.isArray
            t.exports = e
        },
        function (t, n, e) {
            var r = e(19),
                o = e(20),
                u = e(26),
                i = Math.ceil,
                c = Math.max
            t.exports = function (t, n, e) {
                n = (e ? o(t, n, e) : void 0 === n) ? 1 : c(u(n), 0)
                var f = null == t ? 0 : t.length
                if (!f || n < 1) return []
                for (var l = 0, a = 0, s = Array(i(f / n)); l < f; ) s[a++] = r(t, l, (l += n))
                return s
            }
        },
        function (t, n) {
            t.exports = function (t, n, e) {
                var r = -1,
                    o = t.length
                n < 0 && (n = -n > o ? 0 : o + n), (e = e > o ? o : e) < 0 && (e += o), (o = n > e ? 0 : (e - n) >>> 0), (n >>>= 0)
                for (var u = Array(o); ++r < o; ) u[r] = t[r + n]
                return u
            }
        },
        function (t, n, e) {
            var r = e(21),
                o = e(22),
                u = e(25),
                i = e(3)
            t.exports = function (t, n, e) {
                if (!i(e)) return !1
                var c = typeof n
                return !!('number' == c ? o(e) && u(n, e.length) : 'string' == c && n in e) && r(e[n], t)
            }
        },
        function (t, n) {
            t.exports = function (t, n) {
                return t === n || (t != t && n != n)
            }
        },
        function (t, n, e) {
            var r = e(23),
                o = e(24)
            t.exports = function (t) {
                return null != t && o(t.length) && !r(t)
            }
        },
        function (t, n, e) {
            var r = e(1),
                o = e(3)
            t.exports = function (t) {
                if (!o(t)) return !1
                var n = r(t)
                return (
                    '[object Function]' == n || '[object GeneratorFunction]' == n || '[object AsyncFunction]' == n || '[object Proxy]' == n
                )
            }
        },
        function (t, n) {
            t.exports = function (t) {
                return 'number' == typeof t && t > -1 && t % 1 == 0 && t <= 9007199254740991
            }
        },
        function (t, n) {
            var e = /^(?:0|[1-9]\d*)$/
            t.exports = function (t, n) {
                var r = typeof t
                return (
                    !!(n = null == n ? 9007199254740991 : n) &&
                    ('number' == r || ('symbol' != r && e.test(t))) &&
                    t > -1 &&
                    t % 1 == 0 &&
                    t < n
                )
            }
        },
        function (t, n, e) {
            var r = e(27)
            t.exports = function (t) {
                var n = r(t),
                    e = n % 1
                return n == n ? (e ? n - e : n) : 0
            }
        },
        function (t, n, e) {
            var r = e(28)
            t.exports = function (t) {
                return t
                    ? (t = r(t)) === 1 / 0 || t === -1 / 0
                        ? 17976931348623157e292 * (t < 0 ? -1 : 1)
                        : t == t
                        ? t
                        : 0
                    : 0 === t
                    ? t
                    : 0
            }
        },
        function (t, n, e) {
            var r = e(3),
                o = e(29),
                u = /^\s+|\s+$/g,
                i = /^[-+]0x[0-9a-f]+$/i,
                c = /^0b[01]+$/i,
                f = /^0o[0-7]+$/i,
                l = parseInt
            t.exports = function (t) {
                if ('number' == typeof t) return t
                if (o(t)) return NaN
                if (r(t)) {
                    var n = 'function' == typeof t.valueOf ? t.valueOf() : t
                    t = r(n) ? n + '' : n
                }
                if ('string' != typeof t) return 0 === t ? t : +t
                t = t.replace(u, '')
                var e = c.test(t)
                return e || f.test(t) ? l(t.slice(2), e ? 2 : 8) : i.test(t) ? NaN : +t
            }
        },
        function (t, n, e) {
            var r = e(1),
                o = e(2)
            t.exports = function (t) {
                return 'symbol' == typeof t || (o(t) && '[object Symbol]' == r(t))
            }
        },
    ])
})

// console.log(VNnum2words(10000))
