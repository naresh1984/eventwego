class HomeController < ApplicationController

def index
     
    d = Date.today
    @search="end_at>='#{d.strftime("%Y-%m-%d")}'"
    #@search="(id>0)"
    @empty_seatch=''
    @date_search=''
    @type_search=''
    @loc_search=''

    if params[:name] != "Enter Keyword" && params[:name].present?
      @search+=" AND (events.name LIKE '%#{params[:name]}%')" 
       @empty_seatch='1'     
    end
   if params[:address].present?
     
      @empty_seatch='1'               
    end

   if params[:page].present?
    @empty_seatch='1'               
    end
  if  params[:dates].present? 
	      @date_search+="AND ("   
	      if  params[:dates][:today].present?  
	      today="(('#{Date.today.strftime("%Y-%m-%d")}' <= end_at) AND (start_at< '#{Date.tomorrow.strftime("%Y-%m-%d")}'))" 
	      @empty_seatch='1'  
	      end
	      if  params[:dates][:tomorrow].present?  
	      tomorrow="(('#{Date.tomorrow.strftime("%Y-%m-%d")}' <= end_at) AND (start_at< '#{(Date.tomorrow+1.days).strftime("%Y-%m-%d")}'))" 
	      @empty_seatch='1'  
	      end 
	      if  params[:dates][:thisweek].present?  
			      thisweek="('#{(d.at_beginning_of_week).strftime("%Y-%m-%d")}' <= end_at) AND (start_at< '#{(d.at_end_of_week+ 1.days).strftime("%Y-%m-%d")}')" 
	      @empty_seatch='1'  
	      end 
             if  params[:dates][:thisweekend].present?  
			      thisweekend="('#{(d.at_end_of_week-1.day).strftime("%Y-%m-%d")}' <= end_at) AND (start_at< '#{(d.at_end_of_week+1.day).strftime("%Y-%m-%d")}')" 
	      @empty_seatch='1'  
	      end 
              if  params[:dates][:nextmonth].present?  
			      nextmonth="('#{(d.end_of_month+1.day)}' <= end_at) AND (start_at< '#{((d.beginning_of_month+2.month)+1.day)}')" 
	      @empty_seatch='1'  
	      end 



         date_i=0          
	 params[:dates].each do |k,name|
           date_i+=1
          
	       if date_i==1
		searchOR=""
	       else              
		searchOR= " OR "
	       end
               if k=="today"
                  @date_search+=searchOR+(today)

               elsif k=='tomorrow'
                     @date_search+=searchOR+(tomorrow)               
               elsif k=='thisweek'
                     @date_search+=searchOR+(thisweek)               
               elsif k=='thisweekend'
                     @date_search+=searchOR+(thisweekend)
               elsif k=='nextmonth'
                     @date_search+=searchOR+(nextmonth)
               end
             
               
	   
	 end
     @date_search+=")" 
     @search+=@date_search

end

if  params[:types].present? 
 @type_search+=' AND ('
 date_i=0          
	 params[:types].each do |id|
           date_i+=1          
	       if date_i==1
		searchOR=""
	       else              
		searchOR= " OR "
	       end
              @type_search+=searchOR+"(event_type_id="+id+")"  
           
           end
 @type_search+=')'

 @search+=@type_search
 @empty_seatch='1'  
end
if  params[:location].present? 
 @loc_search+=' AND ('
 date_i=0          
	 params[:location].each do |loc|
           date_i+=1          
	       if date_i==1
		searchOR=""
	       else              
		searchOR= " OR "
	       end
              @loc_search+=searchOR+"(city LIKE '%#{loc}%')"              
           
           end
 @loc_search+=')'

 @search+=@loc_search
 @empty_seatch='1'  
end


     if @empty_seatch.empty?
     
     @today_count=today_count
     @tomorrow_count=tomorrow_count
     @thisweek_count=thisweek_count
     @weekend_count=weekend_count
     @nextmonth_count=nextmonth_count
     @all_events=all_events
     @all_types=all_types
     @all_location=all_location    
     #@typecount= @all_types.map { |h| h[:counts] }.sum
     #@locount=  @all_location.map { |h| h[:counts] }.sum 
     @typecount=0  
     @locount=0 
     @all_types.each do |ty|
     @typecount +=ty.counts.to_i
     end
     @all_location.each do |ty|
     @locount +=ty.counts.to_i
     end
     
     end 
     
  if params[:address].present?   
    results = Geocoder.search(params[:address])  
 #@events=Event.joins(:event_type).select('events.id,events.name,events.start_at,events.end_at,events.start_hour,events.start_minute,events.end_hour,events.end_minute,events.address,events.avatar,event_types.name as type').where(@search).near([results[0].latitude,results[0].longitude],25).paginate(:page => params[:page],:per_page => 10).order("start_at ASC")
  @events=Event.joins(:event_type).select('events.id,events.name,events.start_at,events.end_at,events.start_hour,events.start_minute,events.end_hour,events.end_minute,events.address,events.avatar_file_name,events.avatar_updated_at,event_types.name as type').where(@search).near([results[0].latitude,results[0].longitude],25).paginate(:page => params[:page],:per_page => 10).order("start_at ASC") 


    else
     @events=Event.joins(:event_type).select('events.id,events.name,events.start_at,events.end_at,events.start_hour,events.start_minute,events.end_hour,events.end_minute,events.address,events.avatar_file_name,events.avatar_updated_at,event_types.name as type').where(@search).paginate(:page => params[:page],:per_page => 10).order("start_at ASC")      
    end

#raise @nextmonth_count.inspect
    
respond_to do |format|
      format.html #{render html: myevents.html.erb }
      #format.json { render json: @userevents }
      format.js { render layout:false }
    end


 end

end
