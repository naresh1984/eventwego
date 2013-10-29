class AddHourToEvents < ActiveRecord::Migration
    def up    
    add_column :events, :start_hour, :string
    add_column :events, :start_minute, :string  
    add_column :events, :start_meridiem, :string
    add_column :events, :end_hour, :string
    add_column :events, :end_minute, :string 
    add_column :events, :end_meridiem, :string
    
  end
  def down
    remove_column :events, :start_hour, :string
    remove_column :events, :start_minute, :string  
    remove_column :events, :start_meridiem, :string
    remove_column :events, :end_hour, :string
    remove_column :events, :end_minute, :string 
    remove_column :events, :end_meridiem, :string
  end
end
