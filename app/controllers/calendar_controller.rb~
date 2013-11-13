class CalendarController < ApplicationController
  
  def index
     

    @month = (params[:month] || (Time.zone || Time).now.month).to_i
    @year = (params[:year] || (Time.zone || Time).now.year).to_i

    @shown_month = Date.civil(@year, @month)
    @first_day_of_week=0;
    @event_strips = Event.select('id,name,start_at,end_at').where("status='1'").event_strips_for_month(@shown_month)
    #raise @event_strips.inspect
    
  end
  
end
