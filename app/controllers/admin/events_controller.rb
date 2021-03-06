class Admin::EventsController < AdminController

  # GET /events
  # GET /events.json
  def index
    @events = Event.where("end_at>='#{Date.today.strftime("%Y-%m-%d")}'").paginate(:page => params[:page],:per_page => 10)

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @events }
    end
end

  # GET /events/1
  # GET /events/1.json
  def show
    @event = Event.find(params[:id])


if params[:status] == "1"
@event.status= 0
else
@event.status= 1
end

if @event.status!=''
 @event.update_attributes(params[:event])
end




    event_language_id=@event.event_language_id.split(",") 
    @EventLanguage = EventLanguage.select('name').find(event_language_id)   
    respond_to do |format|
      format.html {render layout:false}# show.html.erb
      format.json { render json: @event }
    end
  end

  # GET /events/new
  # GET /events/new.json
  def new
    @event = Event.new
    @EventType = EventType   
    @EventLanguage = EventLanguage

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @event }
    end
  end

  # GET /events/1/edit
  def edit
    @event = Event.find(params[:id])  
    @event.event_language_id=@event.event_language_id.split(",")   
    @EventType = EventType
    @EventLanguage = EventLanguage
  end

  # POST /events
  # POST /events.json
  def create    
     @event = Event.new(params[:event]) 
     @EventType = EventType.all
     @EventLanguage = EventLanguage.all   
     @event.event_type_id= params[:event_type_id] 
     @event.event_language_id= params[:event_language_id].join(',')
     results = Geocoder.search(params[:event][:address]) 
     #raise   results[0].latitude.inspect 
     @event.latitude= results[0].latitude
     @event.longitude=  results[0].longitude
     @event.city=  results[0].city
     @event.state=  results[0].state
     @event.country=  results[0].country
     @event.zipcode=  results[0].postal_code 
     @event.start_hour= params[:start_hour] 
     @event.start_minute=params[:start_minute]
     @event.start_meridiem=params[:start_meridiem]
     @event.end_hour= params[:end_hour] 
     @event.end_minute=params[:end_minute]
     @event.end_meridiem=params[:end_meridiem]
     @event.user_id=current_user.id
     @event.status=1.to_i
     #Event.uploadfile(params[:event][:avatar])


     #uploaded_io = params[:event][:avatar]
  #File.open(Rails.root.join('public', 'uploads', uploaded_io.original_filename), 'w') do |file|
    #file.write(uploaded_io.read)
  #end
   # raise     @event.inspect
    #name =params[:event][:name] 
   # raise  @event.event_language_id.inspect
    respond_to do |format|
      if @event.save
        @UserSigned = UserSigned.new
        @UserSigned.user_id=current_user.id
        @UserSigned.event_id=@event.id
        @UserSigned.save
        format.html { redirect_to admin_events_path, notice: 'Event was successfully created.' }
        format.json
      else


        format.html { render action: "new" }
        format.json 
      end
    end
  end

  # PUT /events/1
  # PUT /events/1.json
  def update
     @event = Event.find(params[:id])
     @event.event_type_id= params[:event_type_id] 
     @event.event_language_id= params[:event_language_id].join(',') 
     @EventType = EventType
    #raise  @EventType.inspect
    @EventLanguage = EventLanguage
#raise  @EventLanguage.inspect
     results = Geocoder.search(params[:event][:address])    
     @event.latitude= results[0].latitude
     @event.longitude=  results[0].longitude
     @event.city=  results[0].city
     @event.state=  results[0].state
     @event.country=  results[0].country
     @event.zipcode=  results[0].postal_code
     @event.start_hour= params[:start_hour] 
     @event.start_minute=params[:start_minute]
     @event.start_meridiem=params[:start_meridiem]
     @event.end_hour= params[:end_hour] 
     @event.end_minute=params[:end_minute]
     @event.end_meridiem=params[:end_meridiem] 
   
    respond_to do |format|
      if @event.update_attributes(params[:event])
        format.html { redirect_to admin_events_path, notice: 'Event was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @event.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /events/1
  # DELETE /events/1.json
  def destroy
    @event = Event.find(params[:id])
    name=@event.name
    @event.destroy

    respond_to do |format|
      format.html { redirect_to admin_events_url , notice: "Event (#{name}) was successfully deleted."}
      format.json { head :no_content }
    end
  end
def myevents 
     @today_count=today_count()
     @tomorrow_count=tomorrow_count()
     @thisweek_count=thisweek_count()
     @weekend_count=weekend_count()
     @nextmonth_count=nextmonth_count()
     @all_events=all_events()
     @all_types=all_types()
     @all_location=all_location()
     @typecount=0  
     @locount=0 
     @all_types.each do |ty|
     @typecount +=ty.counts.to_i
     end
     @all_location.each do |ty|
     @locount +=ty.counts.to_i
     end

if params[:status].present?
@event = Event.find(params[:event_id])

if params[:status] == "1"
@event.status= 0
else
@event.status= 1
end
 @event.update_attributes(params[:event])
end


     @userevents = Event.select('id,name,start_at,status,user_id').where("user_id='#{params[:id]}' AND end_at>='#{Date.today.strftime("2000-%m-%d")}'").paginate(:page => params[:page],:per_page => 10)

     @user=User.find(params[:id]); 
     #raise @userevents.inspect
     respond_to do |format|
      format.html  {render layout:false}
      #format.json { render json: @userevents }
      format.js { render layout:false }
    end

end
def eventssigned 
     @today_count=today_count()
     @tomorrow_count=tomorrow_count()
     @thisweek_count=thisweek_count()
     @weekend_count=weekend_count()
     @nextmonth_count=nextmonth_count()
     @all_events=all_events()
     @all_types=all_types()
     @all_location=all_location()
     @typecount=0  
     @locount=0 
     @all_types.each do |ty|
     @typecount +=ty.counts.to_i
     end
     @all_location.each do |ty|
     @locount +=ty.counts.to_i
     end
     @eventssigned = UserSigned.joins(:user,:event).where(:user_id=>current_user.id).paginate(:page => params[:page],:per_page => 10) 
     #raise @eventssigned.inspect
  respond_to do |format|
      #format.html { render layout:false } #{render html: eventssigned.html.erb }
      #format.json { render json: @eventssigned }
      format.js { render layout:false }
    end

end

def status 
@event = Event.find(params[:id])

if params[:status] == "1"
@event.status= 0
else
@event.status= 1
end

  respond_to do |format|
      if @event.update_attributes(params[:event])
        format.html { redirect_to admin_events_path(:page=>params[:page]), notice: "Event(#{@event.name}) was successfully updated." }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @event.errors, status: :unprocessable_entity }
      end
    end



end 


end
