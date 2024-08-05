import './bootstrap';
import Chart from 'chart.js/auto';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

window.FullCalendar = {
    Calendar: Calendar,
    plugins: {
        dayGrid: dayGridPlugin,
        timeGrid: timeGridPlugin,
        list: listPlugin,
        interaction: interactionPlugin
    }
};

