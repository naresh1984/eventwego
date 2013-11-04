class ApplicationController < ActionController::Base
  protect_from_forgery
 helper_method :all_events
 helper_method :today_count
 helper_method :tomorrow_count
 helper_method :thisweek_count
 helper_method :weekend_count
 helper_method :nextmonth_count
 helper_method :all_types
 helper_method :all_location

 #raise @today_count.inspect
 def all_events
    return @all_events if defined?(@all_events)
    @event_start=Event.select('start_at').order('start_at ASC').limit(1)
    @event_end=Event.select('end_at').order('end_at DESC').limit(1)
   #raise @event_start[0].start_at.inspect
   @all_events=Event.select('id').where("end_at>='#{Date.today.strftime("%Y-%m-%d")}'").events_for_date_range(@event_start[0].start_at.strftime("%Y-%m-%d"), (@event_end[0].end_at+1.days).strftime("%Y-%m-%d")).count
  end
 
  def today_count
    return @today_count if defined?(@today_count)
    @today_count=Event.select('id').events_for_date_range(Date.today.strftime("%Y-%m-%d"), Date.tomorrow.strftime("%Y-%m-%d")).count

  end
 def tomorrow_count
    return @tomorrow_count if defined?(@tomorrow_count)
   @tomorrow_count=Event.select('id').events_for_date_range(Date.tomorrow.strftime("%Y-%m-%d"), (Date.tomorrow + 1.days).strftime("%Y-%m-%d")).count  
  end
def thisweek_count
     d = Date.today  
    return @thisweek_count if defined?(@thisweek_count)
    @thisweek_count=Event.select('id').events_for_date_range((d.at_beginning_of_week).strftime("%Y-%m-%d"), (d.at_end_of_week+ 1.days).strftime("%Y-%m-%d")).count 
   
  end

def weekend_count
 d = Date.today  
    return @weekend_count if defined?(@weekend_count)
   @weekend_count=Event.select('id,name').events_for_date_range((d.at_end_of_week-1.day).strftime("%Y-%m-%d"), (d.at_end_of_week+1.day).strftime("%Y-%m-%d")).count 
  #raise  d.at_end_of_week.strftime("%Y-%m-%d").inspect  
  end

def nextmonth_count
 d = Date.today  
    return @nextmonth_count if defined?(@nextmonth_count)
     @nextmonth_count = Event.select('id').events_for_date_range(d.end_of_month+1.day,((d.beginning_of_month+2.month)+1.day)).count
  end
def all_types
    @event_start=Event.select('start_at').order('start_at ASC').limit(1)
    @event_end=Event.select('end_at').order('end_at DESC').limit(1)  
@all_types=Event.select('event_type_id,count(event_type_id) as count').where("('#{@event_start[0].start_at}'<=end_at) AND (start_at< '#{@event_end[0].end_at+ 1.days}') AND end_at>='#{Date.today.strftime("%Y-%m-%d")}' " ).group("events.event_type_id")

  end
def all_location
    @event_start=Event.select('start_at').order('start_at ASC').limit(1)
    @event_end=Event.select('end_at').order('end_at DESC').limit(1)  
@all_location=Event.select('city,count(city) as count').where("('#{@event_start[0].start_at}'<=end_at) AND (start_at< '#{@event_end[0].end_at+ 1.days}') AND city!='' AND end_at>='#{Date.today.strftime("%Y-%m-%d")}' "  ).group("events.city")


  end

   



end
