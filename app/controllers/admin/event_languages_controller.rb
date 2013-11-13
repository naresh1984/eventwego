class Admin::EventLanguagesController < AdminController
 # GET /event_languages
  # GET /event_languages.json
  def index
    @event_languages = EventLanguage.paginate(:page => params[:page],:per_page => 10)

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @event_languages }
    end
  end

  # GET /event_languages/1
  # GET /event_languages/1.json
  def show
    @event_language = EventLanguage.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @event_language }
    end
  end

  # GET /event_languages/new
  # GET /event_languages/new.json
  def new
    @event_language = EventLanguage.new

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @event_language }
    end
  end

  # GET /event_languages/1/edit
  def edit
    @event_language = EventLanguage.find(params[:id])
  end

  # POST /event_languages
  # POST /event_languages.json
  def create
    @event_language = EventLanguage.new(params[:event_language])

    respond_to do |format|
      if @event_language.save
        format.html { redirect_to admin_event_languages_path, notice: 'Event language was successfully created.' }
        format.json { render json: @event_language, status: :created, location: @event_language }
      else
        format.html { render action: "new" }
        format.json { render json: @event_language.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /event_languages/1
  # PUT /event_languages/1.json
  def update
    @event_language = EventLanguage.find(params[:id])

    respond_to do |format|
      if @event_language.update_attributes(params[:event_language])
        format.html { redirect_to admin_event_languages_path, notice: 'Event language was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @event_language.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /event_languages/1
  # DELETE /event_languages/1.json
  def destroy
    @event_language = EventLanguage.find(params[:id])
    name=@event_language.name
    @event_language.destroy

    respond_to do |format|
      format.html { redirect_to admin_event_languages_url ,notice: "Event language (#{name}) was successfully deleted." }
      format.json { head :no_content }
    end
  end

end
