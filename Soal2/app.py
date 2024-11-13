from flask import Flask, jsonify, render_template, send_file
from datetime import datetime

app = Flask(__name__)

@app.get('/')
def index():
    # Serve the HTML page
    return send_file('web.html')

@app.route('/get_data', methods=['GET'])
def get_data():
    # Data suhu, kelembaban, dan kecerahan
    suhumax = [30, 32, 31]
    suhumin = [20, 21, 22]
    suhurata = [25, 27, 26]
    humid = [65, 60, 70]
    brightness = [100, 120, 110]
    timestamps = ['2024-11-01 12:00:00', '2024-11-01 12:30:00', '2024-11-01 13:00:00']

    timestamps = [datetime.strptime(ts, "%Y-%m-%d %H:%M:%S") for ts in timestamps]

    nilai_suhu_max_humid_max = []
    month_year_max = []

    for idx, (max_temp, min_temp, rata_temp, hum, bright, ts) in enumerate(zip(suhumax, suhumin, suhurata, humid, brightness, timestamps)):
        entry = {
            "idx": idx,
            "suhu": rata_temp,
            "humid": hum,
            "kecerahan": bright,
            "timestamp": ts.isoformat()
        }
        nilai_suhu_max_humid_max.append(entry)

        month_year = ts.strftime('%Y-%m')
        if not any(d['month_year'] == month_year for d in month_year_max):
            month_year_max.append({"month_year": month_year})

    output = {
        "nilai_suhu_max_humid_max": nilai_suhu_max_humid_max,
        "month_year_max": month_year_max
    }

    return jsonify(output)

if __name__ == '__main__':
    app.run(debug=True)
