@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-color: #9ABAD9;
        border-spacing: 0;
    }
    .tg td {
        background-color: #EBF5FF;
        border-color: #9ABAD9;
        border-style: solid;
        border-width: 1px;
        color: #444;
        font-family: Arial, sans-serif;
        font-size: 14px;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }
    .tg th {
        background-color: #409cff;
        border-color: #9ABAD9;
        border-style: solid;
        border-width: 1px;
        color: #fff;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }
    .tg .tg-lboi {
        border-color: inherit;
        text-align: left;
        vertical-align: middle
    }
    .tg .tg-9wq8 {
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }
    .tg .tg-item {
        background-color: #00d2cb;
        border-color: inherit;
        text-align: left;
        vertical-align: top
    }
    .tg .tg-7btt {
        border-color: inherit;
        font-weight: bold;
        text-align: center;
        vertical-align: top
    }
    .tg .tg-lku7 {
        background-color: #00d2cb;
        border-color: inherit;
        font-weight: bold;
        text-align: center;
        vertical-align: top
    }
    .tg .tg-uzvj {
        border-color: inherit;
        font-weight: bold;
        text-align: center;
        vertical-align: middle
    }
    .tg .tg-fymr {
        border-color: inherit;
        font-weight: bold;
        text-align: left;
        vertical-align: top
    }
    .tg .tg-0pky {
        border-color: inherit;
        text-align: left;
        vertical-align: top
    }
    .tg .tg-96sx {
        background-color: #00d2cb;
        border-color: inherit;
        font-weight: bold;
        text-align: left;
        vertical-align: top
    }
    .tg .tg-g7sd {
        border-color: inherit;
        font-weight: bold;
        text-align: left;
        vertical-align: middle
    }
</style>
<table class="tg">
    <thead>
        <tr>
            <th class="tg-7btt">Template</th>
            <th class="tg-7btt">Metric Type Name</th>
            <th class="tg-7btt">Unit</th>
            <th class="tg-7btt">State</th>
            <th class="tg-7btt">Workplace</th>
            <th class="tg-lku7">Total 12 months</th>
            <th class="tg-7btt">Jan</th>
            <th class="tg-7btt">Feb</th>
            <th class="tg-7btt">Mar</th>
            <th class="tg-7btt">Apr</th>
            <th class="tg-7btt">May</th>
            <th class="tg-7btt">June</th>
            <th class="tg-7btt">July</th>
            <th class="tg-7btt">Aug</th>
            <th class="tg-7btt">Sept</th>
            <th class="tg-7btt">Oct</th>
            <th class="tg-7btt">Nov</th>
            <th class="tg-7btt">Dec</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="tg-uzvj" rowspan="21">1.3 Hazardous Waste</td>
            <td class="tg-uzvj" rowspan="6">01. Waste blasting material containing hazardous substances</td>
            <td class="tg-lboi" rowspan="6">kg</td>
            <td class="tg-lboi" rowspan="6">Solid</td>
            <td class="tg-fymr">NZ-O</td>
            <td class="tg-item">780</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">60</td>
            <td class="tg-0pky">70</td>
            <td class="tg-0pky">80</td>
            <td class="tg-0pky">90</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">110</td>
            <td class="tg-0pky">120</td>
        </tr>
        <tr>
            <td class="tg-fymr">SG-O</td>
            <td class="tg-item">1280</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">56.66</td>
            <td class="tg-0pky">76.666</td>
            <td class="tg-0pky">96.66</td>
            <td class="tg-0pky">116.666</td>
            <td class="tg-0pky">136.66</td>
            <td class="tg-0pky">156.66</td>
            <td class="tg-0pky">176.66</td>
            <td class="tg-0pky">196.66</td>
            <td class="tg-0pky">216.66</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-HO</td>
            <td class="tg-item">75</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">1</td>
            <td class="tg-0pky">2</td>
            <td class="tg-0pky">3</td>
            <td class="tg-0pky">4</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">6</td>
            <td class="tg-0pky">7</td>
            <td class="tg-0pky">8</td>
            <td class="tg-0pky">9</td>
            <td class="tg-0pky">10</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF1</td>
            <td class="tg-item">390.5</td>
            <td class="tg-0pky">5.5</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">15</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">25</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">35</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">45</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">55</td>
            <td class="tg-0pky">60</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF2</td>
            <td class="tg-item">5290</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">195</td>
            <td class="tg-0pky">290</td>
            <td class="tg-0pky">385</td>
            <td class="tg-0pky">480</td>
            <td class="tg-0pky">575</td>
            <td class="tg-0pky">670</td>
            <td class="tg-0pky">765</td>
            <td class="tg-0pky">860</td>
            <td class="tg-0pky">955</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF3</td>
            <td class="tg-item">394</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">14</td>
            <td class="tg-0pky">18</td>
            <td class="tg-0pky">22</td>
            <td class="tg-0pky">26</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">34</td>
            <td class="tg-0pky">38</td>
            <td class="tg-0pky">42</td>
            <td class="tg-0pky">46</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">54</td>
        </tr>
        <tr>
            <td class="tg-fymr" colspan="3"></td>
            <td class="tg-0pky">Total per Mon</td>
            <td class="tg-96sx">8209.5</td>
            <td class="tg-fymr">65.5</td>
            <td class="tg-fymr">59</td>
            <td class="tg-fymr">204</td>
            <td class="tg-fymr">335.666666666667</td>
            <td class="tg-fymr">470.666666666667</td>
            <td class="tg-fymr">605.666666666667</td>
            <td class="tg-fymr">740.666666666667</td>
            <td class="tg-fymr">875.666666666667</td>
            <td class="tg-fymr">1010.66666666667</td>
            <td class="tg-fymr">1145.66666666667</td>
            <td class="tg-fymr">1280.66666666667</td>
            <td class="tg-fymr">1415.66666666667</td>
        </tr>
        <tr>
            <td class="tg-uzvj" rowspan="6">02. Spent grinding bodies and grinding materials</td>
            <td class="tg-lboi" rowspan="6">kg</td>
            <td class="tg-lboi" rowspan="6">Solid</td>
            <td class="tg-fymr">NZ-O</td>
            <td class="tg-item">780</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">60</td>
            <td class="tg-0pky">70</td>
            <td class="tg-0pky">80</td>
            <td class="tg-0pky">90</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">110</td>
            <td class="tg-0pky">120</td>
        </tr>
        <tr>
            <td class="tg-fymr">SG-O</td>
            <td class="tg-item">1280</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">56.6666666666667</td>
            <td class="tg-0pky">76.6666666666667</td>
            <td class="tg-0pky">96.6666666666667</td>
            <td class="tg-0pky">116.666666666667</td>
            <td class="tg-0pky">136.666666666667</td>
            <td class="tg-0pky">156.666666666667</td>
            <td class="tg-0pky">176.666666666667</td>
            <td class="tg-0pky">196.666666666667</td>
            <td class="tg-0pky">216.666666666667</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-HO</td>
            <td class="tg-item">75</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">1</td>
            <td class="tg-0pky">2</td>
            <td class="tg-0pky">3</td>
            <td class="tg-0pky">4</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">6</td>
            <td class="tg-0pky">7</td>
            <td class="tg-0pky">8</td>
            <td class="tg-0pky">9</td>
            <td class="tg-0pky">10</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF1</td>
            <td class="tg-item">390.5</td>
            <td class="tg-0pky">5.5</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">15</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">25</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">35</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">45</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">55</td>
            <td class="tg-0pky">60</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF2</td>
            <td class="tg-item">5290</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">195</td>
            <td class="tg-0pky">290</td>
            <td class="tg-0pky">385</td>
            <td class="tg-0pky">480</td>
            <td class="tg-0pky">575</td>
            <td class="tg-0pky">670</td>
            <td class="tg-0pky">765</td>
            <td class="tg-0pky">860</td>
            <td class="tg-0pky">955</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF3</td>
            <td class="tg-item">394</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">14</td>
            <td class="tg-0pky">18</td>
            <td class="tg-0pky">22</td>
            <td class="tg-0pky">26</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">34</td>
            <td class="tg-0pky">38</td>
            <td class="tg-0pky">42</td>
            <td class="tg-0pky">46</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">54</td>
        </tr>
        <tr>
            <td class="tg-fymr" colspan="3"></td>
            <td class="tg-0pky">Total per Mon</td>
            <td class="tg-96sx">8209.5</td>
            <td class="tg-fymr">65.5</td>
            <td class="tg-fymr">59</td>
            <td class="tg-fymr">204</td>
            <td class="tg-fymr">335.666666666667</td>
            <td class="tg-fymr">470.666666666667</td>
            <td class="tg-fymr">605.666666666667</td>
            <td class="tg-fymr">740.666666666667</td>
            <td class="tg-fymr">875.666666666667</td>
            <td class="tg-fymr">1010.66666666667</td>
            <td class="tg-fymr">1145.66666666667</td>
            <td class="tg-fymr">1280.66666666667</td>
            <td class="tg-fymr">1415.66666666667</td>
        </tr>
        <tr>
            <td class="tg-uzvj" rowspan="6">03. Discarded welding rod</td>
            <td class="tg-lboi" rowspan="6">kg</td>
            <td class="tg-lboi" rowspan="6">Solid</td>
            <td class="tg-fymr">NZ-O</td>
            <td class="tg-item">780</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">60</td>
            <td class="tg-0pky">70</td>
            <td class="tg-0pky">80</td>
            <td class="tg-0pky">90</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">110</td>
            <td class="tg-0pky">120</td>
        </tr>
        <tr>
            <td class="tg-fymr">SG-O</td>
            <td class="tg-item">1280</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">56.6666666666667</td>
            <td class="tg-0pky">76.6666666666667</td>
            <td class="tg-0pky">96.6666666666667</td>
            <td class="tg-0pky">116.666666666667</td>
            <td class="tg-0pky">136.666666666667</td>
            <td class="tg-0pky">156.666666666667</td>
            <td class="tg-0pky">176.666666666667</td>
            <td class="tg-0pky">196.666666666667</td>
            <td class="tg-0pky">216.666666666667</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-HO</td>
            <td class="tg-item">75</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">1</td>
            <td class="tg-0pky">2</td>
            <td class="tg-0pky">3</td>
            <td class="tg-0pky">4</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">6</td>
            <td class="tg-0pky">7</td>
            <td class="tg-0pky">8</td>
            <td class="tg-0pky">9</td>
            <td class="tg-0pky">10</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF1</td>
            <td class="tg-item">390.5</td>
            <td class="tg-0pky">5.5</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">15</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">25</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">35</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">45</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">55</td>
            <td class="tg-0pky">60</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF2</td>
            <td class="tg-item">5290</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">195</td>
            <td class="tg-0pky">290</td>
            <td class="tg-0pky">385</td>
            <td class="tg-0pky">480</td>
            <td class="tg-0pky">575</td>
            <td class="tg-0pky">670</td>
            <td class="tg-0pky">765</td>
            <td class="tg-0pky">860</td>
            <td class="tg-0pky">955</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF3</td>
            <td class="tg-item">394</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">14</td>
            <td class="tg-0pky">18</td>
            <td class="tg-0pky">22</td>
            <td class="tg-0pky">26</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">34</td>
            <td class="tg-0pky">38</td>
            <td class="tg-0pky">42</td>
            <td class="tg-0pky">46</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">54</td>
        </tr>
        <tr>
            <td class="tg-fymr" colspan="3"></td>
            <td class="tg-0pky">Total per Mon</td>
            <td class="tg-96sx">8209.5</td>
            <td class="tg-fymr">65.5</td>
            <td class="tg-fymr">59</td>
            <td class="tg-fymr">204</td>
            <td class="tg-fymr">335.666666666667</td>
            <td class="tg-fymr">470.666666666667</td>
            <td class="tg-fymr">605.666666666667</td>
            <td class="tg-fymr">740.666666666667</td>
            <td class="tg-fymr">875.666666666667</td>
            <td class="tg-fymr">1010.66666666667</td>
            <td class="tg-fymr">1145.66666666667</td>
            <td class="tg-fymr">1280.66666666667</td>
            <td class="tg-fymr">1415.66666666667</td>
        </tr>
        <tr>
            <td class="tg-g7sd" rowspan="7">1.2 Industrial Waste</td>
            <td class="tg-uzvj" rowspan="6">1.1 Rockwool</td>
            <td class="tg-lboi" rowspan="6">kg</td>
            <td class="tg-9wq8" rowspan="6">Solid</td>
            <td class="tg-fymr">NZ-O</td>
            <td class="tg-item">780</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">60</td>
            <td class="tg-0pky">70</td>
            <td class="tg-0pky">80</td>
            <td class="tg-0pky">90</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">110</td>
            <td class="tg-0pky">120</td>
        </tr>
        <tr>
            <td class="tg-fymr">SG-O</td>
            <td class="tg-item">1280</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">56.6666666666667</td>
            <td class="tg-0pky">76.6666666666667</td>
            <td class="tg-0pky">96.6666666666667</td>
            <td class="tg-0pky">116.666666666667</td>
            <td class="tg-0pky">136.666666666667</td>
            <td class="tg-0pky">156.666666666667</td>
            <td class="tg-0pky">176.666666666667</td>
            <td class="tg-0pky">196.666666666667</td>
            <td class="tg-0pky">216.666666666667</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-HO</td>
            <td class="tg-item">75</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">0</td>
            <td class="tg-0pky">1</td>
            <td class="tg-0pky">2</td>
            <td class="tg-0pky">3</td>
            <td class="tg-0pky">4</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">6</td>
            <td class="tg-0pky">7</td>
            <td class="tg-0pky">8</td>
            <td class="tg-0pky">9</td>
            <td class="tg-0pky">10</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF1</td>
            <td class="tg-item">390.5</td>
            <td class="tg-0pky">5.5</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">15</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">25</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">35</td>
            <td class="tg-0pky">40</td>
            <td class="tg-0pky">45</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">55</td>
            <td class="tg-0pky">60</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF2</td>
            <td class="tg-item">5290</td>
            <td class="tg-0pky">10</td>
            <td class="tg-0pky">5</td>
            <td class="tg-0pky">100</td>
            <td class="tg-0pky">195</td>
            <td class="tg-0pky">290</td>
            <td class="tg-0pky">385</td>
            <td class="tg-0pky">480</td>
            <td class="tg-0pky">575</td>
            <td class="tg-0pky">670</td>
            <td class="tg-0pky">765</td>
            <td class="tg-0pky">860</td>
            <td class="tg-0pky">955</td>
        </tr>
        <tr>
            <td class="tg-fymr">VN-TF3</td>
            <td class="tg-item">394</td>
            <td class="tg-0pky">20</td>
            <td class="tg-0pky">14</td>
            <td class="tg-0pky">18</td>
            <td class="tg-0pky">22</td>
            <td class="tg-0pky">26</td>
            <td class="tg-0pky">30</td>
            <td class="tg-0pky">34</td>
            <td class="tg-0pky">38</td>
            <td class="tg-0pky">42</td>
            <td class="tg-0pky">46</td>
            <td class="tg-0pky">50</td>
            <td class="tg-0pky">54</td>
        </tr>
        <tr>
            <td class="tg-lboi" colspan="3"></td>
            <td class="tg-0pky">Total per Mon</td>
            <td class="tg-96sx">8209.5</td>
            <td class="tg-fymr">65.5</td>
            <td class="tg-fymr">59</td>
            <td class="tg-fymr">204</td>
            <td class="tg-fymr">335.666666666667</td>
            <td class="tg-fymr">470.666666666667</td>
            <td class="tg-fymr">605.666666666667</td>
            <td class="tg-fymr">740.666666666667</td>
            <td class="tg-fymr">875.666666666667</td>
            <td class="tg-fymr">1010.66666666667</td>
            <td class="tg-fymr">1145.66666666667</td>
            <td class="tg-fymr">1280.66666666667</td>
            <td class="tg-fymr">1415.66666666667</td>
        </tr>
        <tr>
            <td class="tg-7btt" colspan="5">TOTAL</td>
            <td class="tg-96sx">32838</td>
            <td class="tg-fymr">262</td>
            <td class="tg-fymr">236</td>
            <td class="tg-fymr">816</td>
            <td class="tg-fymr">1342.66666666667</td>
            <td class="tg-fymr">1882.66666666667</td>
            <td class="tg-fymr">2422.66666666667</td>
            <td class="tg-fymr">2962.66666666667</td>
            <td class="tg-fymr">3502.66666666667</td>
            <td class="tg-fymr">4042.66666666667</td>
            <td class="tg-fymr">4582.66666666667</td>
            <td class="tg-fymr">5122.66666666667</td>
            <td class="tg-fymr">5662.66666666667</td>
        </tr>
    </tbody>
</table>

@endsection
