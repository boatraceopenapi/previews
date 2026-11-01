# レスポンススキーマ（直前情報）

> ⚠️ 本 API は非推奨です。新しく使う場合は後継の [boatraceopenapi/api](https://github.com/boatraceopenapi/api) を利用してください。

## エンドポイント

| バージョン | URL | 対応期間 |
|---|---|---|
| v3 | `https://boatraceopenapi.github.io/previews/v3/YYYY/YYYYMMDD.json` | 2016年08月01日以降 |
| v2 | `https://boatraceopenapi.github.io/previews/v2/YYYY/YYYYMMDD.json` | 2016年10月01日以降 |

- 日付は日本標準時（JST）基準です。データがない日付は HTTP 404 を返します。
- `today.json`（当日）と `yesterday.json`（前日）もあります（例: `/previews/v3/today.json`）。
- 約 3 時間ごとに当日分と前日分を取得し直して上書きします。
- 出走表は [programs](https://github.com/boatraceopenapi/programs)、結果は [results](https://github.com/boatraceopenapi/results) で配信しています。3 つは同じ日付・同じレースの並びで対応しています。

---

## JSON 全体構造（v3）

```
{
  "previews": [
    {
      "date": "2026-05-01",
      "stadium_number": 1,
      "number": 1,
      ...
      "boats": [
        { "racer_boat_number": 1, ... },
        ...
      ]
    },
    ...
  ]
}
```

- `previews` はその日の全レースの配列です。
- `boats` は出走艇の配列で、通常は `racer_boat_number`（枠番）`1`〜`6` の 6 要素です（例外は「[データの注意点](#データの注意点)」を参照）。
- レースのキーは `(date, stadium_number, number)`、出走艇のキーはそれに `racer_boat_number` を加えたものです。

---

## レース

| フィールド | 型 | 説明 |
|---|---|---|
| `date` | string | 開催日（`YYYY-MM-DD`） |
| `stadium_number` | int | レース場（`1`〜`24`） |
| `number` | int | レース番号（`1`〜`12`） |
| `wind_speed` | int | 風速（m） |
| `wind_direction_number` | int | 風向（`1`〜`17`） |
| `wave_height` | int | 波高（cm） |
| `weather_number` | int | 天候 |
| `air_temperature` | float | 気温（℃） |
| `water_temperature` | float | 水温（℃） |

## 出走艇（`boats[]`）

| フィールド | 型 | 説明 |
|---|---|---|
| `racer_boat_number` | int | 枠番（`1`〜`6`） |
| `racer_course_number` | int | スタート展示の進入コース（`1`〜`6`）。枠番と一致するとは限りません |
| `racer_start_timing` | float | スタート展示のスタートタイミング。フライングは負の値 |
| `racer_weight` | float | 体重（kg） |
| `racer_weight_adjustment` | float | 調整重量（kg） |
| `racer_exhibition_time` | float | 展示タイム（秒） |
| `racer_tilt_adjustment` | float | チルト角度 |

---

## コード値

`stadium_number`・`wind_direction_number`・`weather_number` の番号は、後継 API と同じ意味です。対応表は後継 API の [コード表](https://boatraceopenapi.github.io/api/v1/codes.md) を参照してください。

- `wind_direction_number` の「北」「東」などは地図上の方角ではなく、スタートラインに対する相対的な向きです。`3`〜`7` が追い風、`11`〜`15` が向かい風、`17` が無風です。

---

## データの注意点

- 中止になったレースは、レースと出走艇の値が `null` です。
- 欠場した艇や直前情報が取れなかった艇は、`racer_course_number`・`racer_start_timing` が `null` で、`racer_exhibition_time`・`racer_weight` が `0` になっていることがあります。`racer_exhibition_time` と `racer_weight` の `0` は、値がないものとして扱ってください。
- 欠場した艇がいるレースで、`racer_boat_number` が `0` の 7 つ目の要素が入っていることがあります（v3 全期間で 25 レース）。この要素は `racer_boat_number`・`racer_course_number`・`racer_start_timing` だけを持ちます。`racer_boat_number` が `1`〜`6` の要素だけを使ってください。
- 直前情報は締切前に分かる情報ですが、本 API は約 3 時間ごとの取得なので、締切前の時点のデータがあるとは限りません。
- 当日のファイルは途中の状態です。確定した過去データとして使うのは、翌々日以降に取得したファイルにしてください。

---

## v2 との違い

出走艇（`boats[]`）のフィールドは v3 と同じです。レースのフィールドは次のとおり名前が違います。

| v3 | v2 |
|---|---|
| `date` | `race_date` |
| `stadium_number` | `race_stadium_number` |
| `number` | `race_number` |
| `wind_speed` | `race_wind` |
| `wind_direction_number` | `race_wind_direction_number` |
| `wave_height` | `race_wave` |
| `weather_number` | `race_weather_number` |
| `air_temperature` | `race_temperature` |
| `water_temperature` | `race_water_temperature` |
