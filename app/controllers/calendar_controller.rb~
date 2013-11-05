class CalendarController < ApplicationController
  
  def index
     

    @month = (params[:month] || (Time.zone || Time).now.month).to_i
    @year = (params[:year] || (Time.zone || Time).now.year).to_i

    @shown_month = Date.civil(@year, @month)
    @first_day_of_week=0;
    @event_strips = Event.select('id,name,start_at,end_at').event_strips_for_month(@shown_month)
    #raise @event_strips.inspect
    
     @today_count=today_count()
     @tomorrow_count=tomorrow_count()
     @thisweek_count=thisweek_count()
     @weekend_count=weekend_count()
     @nextmonth_count=nextmonth_count()    
     @all_events=all_events()
     @all_types=all_types()
     @all_location=all_location()
     @typecount= @all_types.map { |h| h[:counts] }.sum
     @locount=  @all_location.map { |h| h[:counts] }.sum

  end
  
end
